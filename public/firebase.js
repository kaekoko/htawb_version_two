const firebaseConfig = {
    apiKey: "AIzaSyBTkGGvxer4UQcfl83RXvWdoL1tUaoaODY",
    authDomain: "myvipwebsite-e2673.firebaseapp.com",
    databaseURL: "https://myvipwebsite-e2673-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "myvipwebsite-e2673",
    storageBucket: "myvipwebsite-e2673.appspot.com",
    messagingSenderId: "428843092516",
    appId: "1:428843092516:web:8d901da7c462375906a6b1",
    measurementId: "G-2HM67SXYMQ"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();