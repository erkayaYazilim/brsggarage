<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Formdan gelen alanlar (name, email, subject, message)
    // @ işareti "undefined index" uyarısını engeller, 
    // ancak daha temiz yaklaşım filter_input kullanmaktır.
    $name    = @$_POST["name"];
    $email   = @$_POST["email"];
    $subject = @$_POST["subject"];
    $message = @$_POST["message"];

    // Basit bir filtreleme yapalım (daha ileri düzey validation önerilir)
    $name    = filter_var($name, FILTER_SANITIZE_STRING);
    $email   = filter_var($email, FILTER_VALIDATE_EMAIL);
    $subject = filter_var($subject, FILTER_SANITIZE_STRING);
    $message = filter_var($message, FILTER_SANITIZE_STRING);

    // Geçersiz email durumunu kontrol edelim
    if (!$email) {
        die("Geçersiz e-posta adresi girdiniz.");
    }

    // Mail gönderilecek adres:
    $to = "emreerkaya112@gmail.com";

    // Mail başlığı
    // Konu boşsa "İletişim Formu" gibi bir şey kullanabilirsiniz.
    $emailSubject = $subject ? $subject : "İletişim Formu";

    // HTML formatlı mail içeriği oluşturma
    // İsteğe göre sade (text) mail de gönderebilirsiniz.
    $mailContent = "
    <html>
    <head>
      <meta charset='UTF-8'>
      <title>$emailSubject</title>
    </head>
    <body>
      <h3>Yeni İletişim Formu Mesajı</h3>
      <p><b>Gönderen Adı:</b> {$name}</p>
      <p><b>E-posta:</b> {$email}</p>
      <p><b>Konu:</b> {$subject}</p>
      <p><b>Mesaj:</b> {$message}</p>
    </body>
    </html>
    ";

    // Mail başlıkları (Headers)
    // "From" ve "Reply-To" ayarları
    $headers  = "From: {$email}\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Mail gönderme
    $success = mail($to, $emailSubject, $mailContent, $headers);

    // Başarılıysa bir teşekkür sayfasına veya mesaja yönlendirebilirsiniz:
    if ($success) {
        echo "Mesajınız başarıyla gönderildi, teşekkürler!";
        // Örnek yönlendirme (5 saniye sonra index'e dön):
        // header("refresh:5;url=index.php");
    } else {
        echo "Mail gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
    }
} else {
    echo "Form verisi POST ile gelmedi.";
}
?>
