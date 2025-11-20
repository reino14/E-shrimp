// Firebase initialization for E-shrimp web
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getAuth, GoogleAuthProvider } from "firebase/auth";
import { getDatabase } from "firebase/database";

const firebaseConfig = {
    apiKey: "AIzaSyCZIu1qXrdtcjZmRrUvWqEG6Ya8_GWV0u0",
    authDomain: "e-shrimp-d72be.firebaseapp.com",
    databaseURL:
        "https://e-shrimp-d72be-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "e-shrimp-d72be",
    storageBucket: "e-shrimp-d72be.firebasestorage.app",
    messagingSenderId: "510132699133",
    appId: "1:510132699133:web:61eb7780d614da98d12394",
    measurementId: "G-DE5781EQSJ",
};

export const app = initializeApp(firebaseConfig);
// Analytics may fail on non-https or unsupported env; ignore errors
try {
    getAnalytics(app);
} catch (_) {}

export const auth = getAuth(app);
export const googleProvider = new GoogleAuthProvider();
export const database = getDatabase(app);
