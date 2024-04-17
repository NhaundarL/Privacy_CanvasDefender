<?php
try {
    $db = new PDO('mysql:host=****;dbname=****;charset=utf8', '****', '****');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        $canvasFingerprint = $data['fp'];
        $noise = implode(',', $data['noise']);
        $IP_publique = $data['ip'];

        // Remplacer les valeurs NULL dans la colonne 'noise' par une chaîne vide
        $noise = $noise === null ? '0,0,0,0' : $noise;

        // Calculer l'empreinte numérique de la colonne 'fingerprint'
        $canvasFingerprintHash = hash('sha256', $canvasFingerprint);

        // Vérifier si une ligne avec la même fingerprint, noise et IP_publique existe déjà
        $checkSql = "SELECT COUNT(*) FROM fingerprints WHERE fingerprint_hash = :canvas_fingerprint_hash AND noise = :noise AND IP_publique = :IP_publique";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->bindParam(':canvas_fingerprint_hash', $canvasFingerprintHash);
        $checkStmt->bindParam(':noise', $noise);
        $checkStmt->bindParam(':IP_publique', $IP_publique);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            echo "Une ligne avec la même fingerprint, noise et IP_publique existe déjà";
        } else {
            $sql = "INSERT INTO fingerprints (fingerprint, fingerprint_hash, noise, IP_publique, created_at) VALUES (:canvas_fingerprint, :canvas_fingerprint_hash, :noise, :IP_publique, NOW())";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':canvas_fingerprint', $canvasFingerprint);
            $stmt->bindParam(':canvas_fingerprint_hash', $canvasFingerprintHash);
            $stmt->bindParam(':noise', $noise);
            $stmt->bindParam(':IP_publique', $IP_publique);

            $stmt->execute();
            echo "New record created successfully";
        }
    }
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
