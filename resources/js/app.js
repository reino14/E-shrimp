import "./bootstrap";
import "./firebase-config";
import "./auth";
import * as firebaseRTDB from "./firebase-rtdb";

// Expose to window for use in Blade templates
if (typeof window !== 'undefined') {
    window.firebaseRTDB = firebaseRTDB;
}
