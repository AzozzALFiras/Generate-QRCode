<?php
// Developer by : Azozz ALFiras

// Get the JSON data containing the QR code image
$data = json_decode(file_get_contents("php://input"));

if ($data && isset($data->image)) {
$qrCodeImage = $data->image;

// Generate a unique filename for the QR code image (e.g., using a timestamp)
$filename = 'qrcode_' . time() . '.png';

// Define the path where you want to save the QR code image on the server
$imagePath = 'uploads/' . $filename;

// Save the QR code image to the server
$imageData = base64_decode(str_replace('data:image/png;base64,', '', $qrCodeImage));
file_put_contents($imagePath, $imageData);

// upload image file to the server for add QR Code to ur image 
$sourceImage = imagecreatefrompng('withoutQRcode-1.png');

// Load the icon image (JPEG)
$iconImage = imagecreatefrompng($imagePath); // Use @ to suppress warnings

if ($iconImage === false) {
// Handle the case where the image cannot be loaded
die('Failed to load the JPEG image.');
} else {
// Resize the icon image to your desired width and height
$newWidth = 470; // Adjust this to your desired width
$newHeight = 470; // Adjust this to your desired height
$resizedIconImage = imagecreatetruecolor($newWidth, $newHeight);

// Calculate the position to place the icon
$iconX = 540; // Adjust this according to your desired position
$iconY = 1390; // Adjust this according to your desired position

// Copy the resized icon image onto the source image
imagecopyresampled($resizedIconImage, $iconImage, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($iconImage), imagesy($iconImage));

// Remove the black color transparency (0, 0, 0)
imagecolortransparent($resizedIconImage, imagecolorallocatealpha($resizedIconImage, 0, 0, 0, 127));

// Copy the icon image onto the source image
imagecopy($sourceImage, $resizedIconImage, $iconX, $iconY, 0, 0, $newWidth, $newHeight);

// Adding text (you can keep your existing text adding code here)

// Save the final image as a JPG
$outputImage = 'uploads/' . time().md5($filename).'.jpg';

imagejpeg($sourceImage, $outputImage);

// Destroy the temporary images
imagedestroy($sourceImage);
imagedestroy($resizedIconImage);
echo $outputImage;
}

// Destroy the original icon image
imagedestroy($iconImage);
} else {
echo "Error: Invalid data";
}
?>
