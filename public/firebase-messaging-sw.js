/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AIzaSyBTkGGvxer4UQcfl83RXvWdoL1tUaoaODY",
    authDomain: "myvipwebsite-e2673.firebaseapp.com",
    databaseURL: "https://myvipwebsite-e2673-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "myvipwebsite-e2673",
    storageBucket: "myvipwebsite-e2673.appspot.com",
    messagingSenderId: "428843092516",
    appId: "1:428843092516:web:8d901da7c462375906a6b1",
    measurementId: "G-2HM67SXYMQ"
    });

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});


