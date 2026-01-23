<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>QR Scanner</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- QR Library (LOCAL FILE) -->
<script src="html5-qrcode.min.js"></script>

<style>
body {
    font-family: Arial, sans-serif;
    background: #0d47a1;
    margin: 0;
    padding: 0;
    color: #fff;
}

.container {
    max-width: 420px;
    margin: auto;
    padding: 20px;
    text-align: center;
}

h2 {
    margin-bottom: 15px;
}

#reader {
    width: 100%;
    max-width: 300px;
    height: 300px;
    margin: auto;
    border: 4px solid #2196f3;
    border-radius: 12px;
    background: #000;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border: none;
    border-radius: 6px;
    background: #2196f3;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #1976d2;
}

input[type=file] {
    margin-top: 12px;
    width: 100%;
    padding: 10px;
    background: #e3f2fd;
    border-radius: 6px;
    border: none;
}

.note {
    margin-top: 12px;
    font-size: 13px;
    color: #bbdefb;
}
</style>
</head>

<body>

<div class="container">
    <h2>QR Code Scanner</h2>

    <div id="reader"></div>

    <button onclick="startScanner()">Start Camera</button>
    <button onclick="stopScanner()">Stop Camera</button>

    <input type="file" id="qr-file" accept="image/*">

    <div class="note">
        Scan QR using camera or upload QR image
    </div>
</div>

<script>
let html5QrCode;

function startScanner() {
    if (html5QrCode) return;

    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" }, // BACK CAMERA
        {
            fps: 10,
            qrbox: { width: 220, height: 220 }
        },
        (decodedText) => {
            stopScanner();

            // AUTO REDIRECT (NO DISPLAY)
            if (decodedText.startsWith("http://") || decodedText.startsWith("https://")) {
                window.location.href = decodedText;
            } else {
                alert("QR data detected:\n" + decodedText);
            }
        },
        () => {}
    ).catch(err => {
        alert("Camera error. Please allow camera access.\n" + err);
        html5QrCode = null;
    });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            html5QrCode = null;
        });
    }
}

// IMAGE UPLOAD SCAN
document.getElementById("qr-file").addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    html5QrCode.scanFile(file, true)
        .then(decodedText => {
            if (decodedText.startsWith("http://") || decodedText.startsWith("https://")) {
                window.location.href = decodedText;
            } else {
                alert("QR data detected:\n" + decodedText);
            }
        })
        .catch(() => {
            alert("Cannot read QR from image");
        });
});
</script>

</body>
</html>
