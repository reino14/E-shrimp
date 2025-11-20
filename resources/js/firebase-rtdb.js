// Firebase Realtime Database helper functions
import { database } from "./firebase-config";
import {
    ref,
    onValue,
    off,
    push,
    set,
    update,
    remove,
    get,
    query,
    orderByChild,
    limitToLast,
    startAt,
    endAt,
    equalTo,
} from "firebase/database";

/**
 * Encode email to safe path format for Firebase RDTB
 * Replaces invalid characters: . # $ [ ]
 * @param {string} email - Email address
 * @returns {string} Encoded email safe for path
 */
function encodeEmailForPath(email) {
    return email
        .replace(/\./g, '_DOT_')
        .replace(/#/g, '_HASH_')
        .replace(/\$/g, '_DOLLAR_')
        .replace(/\[/g, '_LBRACKET_')
        .replace(/\]/g, '_RBRACKET_');
}

/**
 * Decode email from safe path format
 * @param {string} encoded - Encoded email
 * @returns {string} Original email
 */
function decodeEmailFromPath(encoded) {
    return encoded
        .replace(/_DOT_/g, '.')
        .replace(/_HASH_/g, '#')
        .replace(/_DOLLAR_/g, '$')
        .replace(/_LBRACKET_/g, '[')
        .replace(/_RBRACKET_/g, ']');
}

/**
 * Listen to real-time sensor data for a specific kolam
 * @param {string} kolamId - Kolam ID
 * @param {function} callback - Callback function that receives data snapshot
 * @returns {function} Unsubscribe function
 */
export function listenSensorData(kolamId, callback) {
    const sensorRef = ref(database, `sensor_data/${kolamId}`);
    
    const unsubscribe = onValue(sensorRef, (snapshot) => {
        const data = snapshot.val();
        if (data) {
            // Convert object to array and sort by timestamp
            const dataArray = Object.entries(data)
                .map(([key, value]) => ({
                    id: key,
                    ...value,
                }))
                .sort((a, b) => (b.timestamp || 0) - (a.timestamp || 0));
            callback(dataArray);
        } else {
            callback([]);
        }
    }, (error) => {
        console.error("Error listening to sensor data:", error);
        callback([]);
    });

    return () => off(sensorRef);
}

/**
 * Get latest sensor data for a kolam
 * @param {string} kolamId - Kolam ID
 * @returns {Promise} Latest sensor data
 */
export async function getLatestSensorData(kolamId) {
    try {
        const sensorRef = ref(database, `sensor_data/${kolamId}`);
        const snapshot = await get(query(sensorRef, orderByChild("timestamp"), limitToLast(1)));
        
        if (snapshot.exists()) {
            const data = snapshot.val();
            const key = Object.keys(data)[0];
            return { id: key, ...data[key] };
        }
        return null;
    } catch (error) {
        console.error("Error getting latest sensor data:", error);
        return null;
    }
}

/**
 * Get historical sensor data for a kolam
 * @param {string} kolamId - Kolam ID
 * @param {number} hours - Number of hours to get data (default: 24)
 * @returns {Promise} Array of sensor data
 */
export async function getHistoricalSensorData(kolamId, hours = 24) {
    try {
        const startTime = Date.now() - hours * 60 * 60 * 1000;
        const sensorRef = ref(database, `sensor_data/${kolamId}`);
        const snapshot = await get(
            query(
                sensorRef,
                orderByChild("timestamp"),
                startAt(startTime)
            )
        );

        if (snapshot.exists()) {
            const data = snapshot.val();
            return Object.entries(data)
                .map(([key, value]) => ({
                    id: key,
                    ...value,
                }))
                .sort((a, b) => (a.timestamp || 0) - (b.timestamp || 0));
        }
        return [];
    } catch (error) {
        console.error("Error getting historical sensor data:", error);
        return [];
    }
}

/**
 * Save sensor data to Firebase
 * @param {string} kolamId - Kolam ID
 * @param {object} sensorData - Sensor data object {ph, suhu, oksigen, salinitas, kualitas_air}
 * @returns {Promise} Result
 */
export async function saveSensorData(kolamId, sensorData) {
    try {
        const sensorRef = ref(database, `sensor_data/${kolamId}`);
        const newDataRef = push(sensorRef);
        
        await set(newDataRef, {
            ...sensorData,
            timestamp: Date.now(),
            waktu: new Date().toISOString(),
        });

        return { success: true, id: newDataRef.key };
    } catch (error) {
        console.error("Error saving sensor data:", error);
        return { success: false, error };
    }
}

/**
 * Get or set threshold for a kolam
 * @param {string} kolamId - Kolam ID
 * @param {string} sensorTipe - Sensor type (ph, suhu, oksigen, salinitas)
 * @param {number|null} nilai - Threshold value (if null, just get)
 * @returns {Promise} Threshold data
 */
export async function setThreshold(kolamId, sensorTipe, nilai = null) {
    try {
        const thresholdRef = ref(database, `thresholds/${kolamId}/${sensorTipe}`);
        
        if (nilai !== null) {
            await set(thresholdRef, {
                nilai,
                sensor_tipe: sensorTipe,
                updated_at: Date.now(),
            });
        }

        const snapshot = await get(thresholdRef);
        return snapshot.exists() ? snapshot.val() : null;
    } catch (error) {
        console.error("Error setting threshold:", error);
        return null;
    }
}

/**
 * Get all thresholds for a kolam
 * @param {string} kolamId - Kolam ID
 * @returns {Promise} Object with all thresholds
 */
export async function getThresholds(kolamId) {
    try {
        const thresholdRef = ref(database, `thresholds/${kolamId}`);
        const snapshot = await get(thresholdRef);
        return snapshot.exists() ? snapshot.val() : {};
    } catch (error) {
        console.error("Error getting thresholds:", error);
        return {};
    }
}

/**
 * Create notification
 * @param {string} kolamId - Kolam ID
 * @param {string} pesan - Notification message
 * @returns {Promise} Result
 */
export async function createNotification(kolamId, pesan) {
    try {
        const notifRef = ref(database, `notifications/${kolamId}`);
        const newNotifRef = push(notifRef);
        
        await set(newNotifRef, {
            pesan,
            status: false, // unread
            waktu: Date.now(),
            timestamp: new Date().toISOString(),
        });

        return { success: true, id: newNotifRef.key };
    } catch (error) {
        console.error("Error creating notification:", error);
        return { success: false, error };
    }
}

/**
 * Get unread notifications for a kolam
 * @param {string} kolamId - Kolam ID
 * @param {number} limit - Limit number of notifications
 * @returns {Promise} Array of notifications
 */
export async function getUnreadNotifications(kolamId, limit = 5) {
    try {
        const notifRef = ref(database, `notifications/${kolamId}`);
        const snapshot = await get(
            query(notifRef, orderByChild("status"), startAt(false), limitToLast(limit))
        );

        if (snapshot.exists()) {
            const data = snapshot.val();
            return Object.entries(data)
                .map(([key, value]) => ({
                    id: key,
                    ...value,
                }))
                .filter((n) => !n.status)
                .sort((a, b) => (b.waktu || 0) - (a.waktu || 0));
        }
        return [];
    } catch (error) {
        console.error("Error getting notifications:", error);
        return [];
    }
}

/**
 * Mark notification as read
 * @param {string} kolamId - Kolam ID
 * @param {string} notifId - Notification ID
 * @returns {Promise} Result
 */
export async function markNotificationAsRead(kolamId, notifId) {
    try {
        const notifRef = ref(database, `notifications/${kolamId}/${notifId}`);
        await update(notifRef, { status: true });
        return { success: true };
    } catch (error) {
        console.error("Error marking notification as read:", error);
        return { success: false, error };
    }
}

/**
 * Save monitoring instruction / briefing data
 * @param {string} kolamId - Kolam ID
 * @param {object} instruction - Instruction payload
 * @returns {Promise} Result
 */
export async function saveMonitoringInstruction(kolamId, instruction = {}) {
    try {
        const instructionRef = ref(database, `monitoring_instructions/${kolamId}`);
        const newInstructionRef = push(instructionRef);
        const now = Date.now();
        const payload = {
            jenis: instruction.jenis || "Monitoring",
            penanggung_jawab: instruction.penanggung_jawab || "",
            peralatan: instruction.peralatan || "",
            perintah: instruction.perintah || "",
            catatan: instruction.catatan || "",
            start_time: instruction.start_time || null,
            created_at: now,
            created_at_iso: new Date(now).toISOString(),
        };

        if (instruction.start_time) {
            const parsed = Date.parse(instruction.start_time);
            if (!Number.isNaN(parsed)) {
                payload.start_timestamp = parsed;
                payload.start_time_iso = new Date(parsed).toISOString();
            }
        }

        await set(newInstructionRef, payload);

        return { success: true, id: newInstructionRef.key };
    } catch (error) {
        console.error("Error saving monitoring instruction:", error);
        return { success: false, error: error.message };
    }
}

/**
 * Get latest monitoring instructions
 * @param {string} kolamId - Kolam ID
 * @param {number} limit - Number of records to return
 * @returns {Promise} Array of instructions
 */
export async function getMonitoringInstructions(kolamId, limit = 5) {
    try {
        const instructionRef = ref(database, `monitoring_instructions/${kolamId}`);
        const snapshot = await get(instructionRef);

        if (snapshot.exists()) {
            const data = snapshot.val();
            return Object.entries(data)
                .map(([key, value]) => ({
                    id: key,
                    ...value,
                }))
                .sort((a, b) => (b.created_at || 0) - (a.created_at || 0))
                .slice(0, limit);
        }

        return [];
    } catch (error) {
        console.error("Error getting monitoring instructions:", error);
        return [];
    }
}

/**
 * Get peternak by email from RDTB
 * @param {string} email - Peternak email
 * @returns {Promise} Peternak data or null
 */
export async function getPeternakByEmail(email) {
    try {
        const encodedEmail = encodeEmailForPath(email);
        const peternakRef = ref(database, `peternaks/${encodedEmail}`);
        const snapshot = await get(peternakRef);
        return snapshot.exists() ? snapshot.val() : null;
    } catch (error) {
        console.error("Error getting peternak:", error);
        return null;
    }
}

/**
 * Check peternak login credentials
 * @param {string} email - Peternak email
 * @param {string} password - Peternak password
 * @returns {Promise} Peternak data if valid, null otherwise
 */
export async function checkPeternakLogin(email, password) {
    try {
        const peternak = await getPeternakByEmail(email);
        if (peternak && peternak.password === password) {
            // Return peternak data without password
            const { password: _, ...peternakData } = peternak;
            return peternakData;
        }
        return null;
    } catch (error) {
        console.error("Error checking peternak login:", error);
        return null;
    }
}

/**
 * Create new peternak (admin only)
 * @param {string} email - Peternak email
 * @param {string} nama - Peternak name
 * @param {string} password - Peternak password
 * @param {string} trackerId - Optional tracker ID
 * @returns {Promise} Result
 */
export async function createPeternak(email, nama, password, trackerId = null) {
    try {
        const encodedEmail = encodeEmailForPath(email);
        const peternakRef = ref(database, `peternaks/${encodedEmail}`);
        
        // Check if email already exists
        const existing = await get(peternakRef);
        if (existing.exists()) {
            return { success: false, error: "Email sudah terdaftar" };
        }
        
        await set(peternakRef, {
            email_peternak: email,
            nama,
            password, // In production, hash this password
            tracker_id: trackerId,
            role: "peternak",
            created_at: Date.now(),
            created_at_iso: new Date().toISOString(),
        });

        return { success: true, email };
    } catch (error) {
        console.error("Error creating peternak:", error);
        return { success: false, error: error.message };
    }
}

/**
 * Get all peternaks (admin only)
 * @returns {Promise} Array of peternaks
 */
export async function getAllPeternaks() {
    try {
        const peternaksRef = ref(database, "peternaks");
        const snapshot = await get(peternaksRef);
        
        if (snapshot.exists()) {
            const data = snapshot.val();
            return Object.entries(data).map(([encodedEmail, peternak]) => ({
                email_peternak: peternak.email_peternak || decodeEmailFromPath(encodedEmail),
                ...peternak,
                password: undefined, // Don't return password
            }));
        }
        return [];
    } catch (error) {
        console.error("Error getting all peternaks:", error);
        return [];
    }
}

/**
 * Delete peternak (admin only)
 * @param {string} email - Peternak email
 * @returns {Promise} Result
 */
export async function deletePeternak(email) {
    try {
        const encodedEmail = encodeEmailForPath(email);
        const peternakRef = ref(database, `peternaks/${encodedEmail}`);
        await remove(peternakRef);
        return { success: true };
    } catch (error) {
        console.error("Error deleting peternak:", error);
        return { success: false, error: error.message };
    }
}

/**
 * Get all forum posts
 * @returns {Promise} Array of forum posts
 */
export async function getAllForumPosts() {
    try {
        const forumRef = ref(database, "forums");
        const snapshot = await get(forumRef);
        
        if (snapshot.exists()) {
            const data = snapshot.val();
            const posts = [];
            
            // Process all posts
            Object.entries(data).forEach(([id, post]) => {
                // Only include main posts (not replies)
                if (!post.post_peternak_id) {
                    posts.push({
                        id: id,
                        forum_id: id,
                        ...post,
                    });
                }
            });
            
            // Add replies to their parent posts
            Object.entries(data).forEach(([id, post]) => {
                if (post.post_peternak_id) {
                    // This is a reply, find parent post
                    const parentPost = posts.find(p => p.id === post.post_peternak_id);
                    if (parentPost) {
                        if (!parentPost.replies) {
                            parentPost.replies = {};
                        }
                        parentPost.replies[id] = {
                            id: id,
                            ...post,
                        };
                    }
                }
            });
            
            // Sort by created_at descending
            return posts.sort((a, b) => {
                const dateA = a.created_at || a.tanggal || 0;
                const dateB = b.created_at || b.tanggal || 0;
                return dateB - dateA;
            });
        }
        return [];
    } catch (error) {
        console.error("Error getting forum posts:", error);
        return [];
    }
}

/**
 * Create new forum post
 * @param {string} judul - Post title
 * @param {string} isi - Post content
 * @param {string} emailPeternak - Peternak udang vaname email (optional for admin)
 * @param {string} postPeternakId - Parent post ID for replies
 * @returns {Promise} Result
 */
export async function createForumPost(judul, isi, emailPeternak = null, postPeternakId = null) {
    try {
        const forumRef = ref(database, "forums");
        const newPostRef = push(forumRef);
        const forumId = newPostRef.key;
        
        await set(newPostRef, {
            forum_id: forumId,
            judul,
            isi,
            tanggal: Date.now(),
            tanggal_iso: new Date().toISOString(),
            email_peternak: emailPeternak,
            post_peternak_id: postPeternakId,
            created_at: Date.now(),
            created_at_iso: new Date().toISOString(),
        });

        return { success: true, forum_id: forumId };
    } catch (error) {
        console.error("Error creating forum post:", error);
        return { success: false, error: error.message };
    }
}

/**
 * Reply to forum post
 * @param {string} parentId - Parent post ID
 * @param {string} isi - Reply content
 * @param {string} emailPeternak - Peternak udang vaname email (optional for admin)
 * @returns {Promise} Result
 */
export async function replyForumPost(parentId, isi, emailPeternak = null) {
    try {
        // Get parent post to get title
        const parentRef = ref(database, `forums/${parentId}`);
        const parentSnapshot = await get(parentRef);
        
        if (!parentSnapshot.exists()) {
            return { success: false, error: "Post tidak ditemukan" };
        }
        
        const parentData = parentSnapshot.val();
        const judul = parentData.judul.startsWith('Re: ') 
            ? parentData.judul 
            : `Re: ${parentData.judul}`;
        
        return await createForumPost(judul, isi, emailPeternak, parentId);
    } catch (error) {
        console.error("Error replying to forum post:", error);
        return { success: false, error: error.message };
    }
}

/**
 * Delete forum post
 * @param {string} forumId - Forum post ID
 * @returns {Promise} Result
 */
export async function deleteForumPost(forumId) {
    try {
        const forumRef = ref(database, `forums/${forumId}`);
        await remove(forumRef);
        return { success: true };
    } catch (error) {
        console.error("Error deleting forum post:", error);
        return { success: false, error: error.message };
    }
}

/**
 * Delete all posts by a peternak
 * @param {string} emailPeternak - Peternak email
 * @returns {Promise} Result
 */
export async function deletePeternakPosts(emailPeternak) {
    try {
        const forumRef = ref(database, "forums");
        const snapshot = await get(query(forumRef, orderByChild("email_peternak"), equalTo(emailPeternak)));
        
        if (snapshot.exists()) {
            const data = snapshot.val();
            const deletePromises = Object.keys(data).map(id => 
                remove(ref(database, `forums/${id}`))
            );
            await Promise.all(deletePromises);
        }
        
        return { success: true };
    } catch (error) {
        console.error("Error deleting peternak posts:", error);
        return { success: false, error: error.message };
    }
}

