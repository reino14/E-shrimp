import { auth } from "./firebase-config";
import {
    signInWithEmailAndPassword,
    onAuthStateChanged,
    signOut,
} from "firebase/auth";
import { checkPeternakLogin, getPeternakByEmail } from "./firebase-rtdb";

function byId(id) {
    return document.getElementById(id);
}

// Email/password login - Check Firebase Auth first (admin), then RDTB (peternak)
const loginForm = byId("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const email = byId("loginEmail").value.trim();
        const password = byId("loginPassword").value;
        const submitBtn = loginForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        try {
            submitBtn.disabled = true;
            submitBtn.textContent = "Memproses...";
            
            // Step 1: Try Firebase Auth (Admin)
            try {
                await signInWithEmailAndPassword(auth, email, password);
                // Success = Admin
                sessionStorage.setItem('userRole', 'admin');
                sessionStorage.setItem('userEmail', email);
                window.location.href = "/admin/dashboard";
                return;
            } catch (authError) {
                // If Firebase Auth fails, check RDTB for Peternak
                console.log("Firebase Auth failed, checking RDTB for peternak...");
            }
            
            // Step 2: Check RDTB for Peternak
            const peternak = await checkPeternakLogin(email, password);
            if (peternak) {
                // Success = Peternak
                sessionStorage.setItem('userRole', 'peternak');
                sessionStorage.setItem('userEmail', email);
                sessionStorage.setItem('userName', peternak.nama || '');
                
                // Sync peternak to Laravel DB before redirect
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    if (csrfToken) {
                        await fetch('/api/sync-peternak', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                email: email,
                                nama: peternak.nama || 'Peternak ' + email,
                            }),
                        });
                    }
                } catch (syncError) {
                    console.error('Error syncing peternak (will retry on dashboard):', syncError);
                    // Continue anyway, will sync on dashboard
                }
                
                window.location.href = "/dashboard";
                return;
            }
            
            // Both failed
            alert("Email atau password salah. Pastikan Anda adalah admin (Firebase Auth) atau peternak yang terdaftar.");
        } catch (err) {
            console.error("Login error:", err);
            alert("Terjadi kesalahan saat login. Silakan coba lagi.");
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// Registration UI removed per request

// Logout handler - clear session and Firebase Auth
const logoutLinks = document.querySelectorAll("[data-logout]");
logoutLinks.forEach((el) => {
    el.addEventListener("click", async (e) => {
        e.preventDefault();
        try {
            // Clear session storage
            sessionStorage.removeItem('userRole');
            sessionStorage.removeItem('userEmail');
            sessionStorage.removeItem('userName');
            
            // Sign out from Firebase Auth (if admin)
            try {
                await signOut(auth);
            } catch {}
            
            window.location.href = "/";
        } catch (err) {
            console.error("Logout error:", err);
            window.location.href = "/";
        }
    });
});

// Redirect to login if not authenticated and page requires auth
const requiresAuth = document.body?.dataset?.requiresAuth === "true";
if (requiresAuth) {
    // Check if user is logged in (either admin via Firebase Auth or peternak via session)
    const userRole = sessionStorage.getItem('userRole');
    const userEmail = sessionStorage.getItem('userEmail');
    
    if (!userRole || !userEmail) {
        // No session, check Firebase Auth
        onAuthStateChanged(auth, (user) => {
            if (!user) {
                window.location.href = "/login";
            } else {
                // Firebase Auth user = Admin
                sessionStorage.setItem('userRole', 'admin');
                sessionStorage.setItem('userEmail', user.email);
            }
        });
    }
    
    // Check role for admin pages
    const isAdminPage = window.location.pathname.startsWith('/admin');
    if (isAdminPage && userRole !== 'admin') {
        window.location.href = "/dashboard";
    }
    
    // Check role for user pages
    const isUserPage = !window.location.pathname.startsWith('/admin') && 
                       window.location.pathname !== '/' && 
                       window.location.pathname !== '/login';
    if (isUserPage && userRole !== 'peternak' && userRole !== 'admin') {
        window.location.href = "/login";
    }
}
