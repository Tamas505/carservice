<?php
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $appointmentDate = $_POST['appointment_date'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO appointments 
            (customer_name, phone, email, appointment_date)
            VALUES
            (:customer_name, :phone, :email, :appointment_date)
        ");

        $stmt->execute([
            ':customer_name' => $customerName,
            ':phone' => $phone,
            ':email' => $email,
            ':appointment_date' => $appointmentDate
        ]);
        $subject = "CarService - Időpontfoglalás visszaigazolása";

        $emailMessage = "
        Kedves $customerName!

        Foglalását sikeresen rögzítettük.

        Időpont: $appointmentDate
        Telefonszám: $phone

        Várjuk szeretettel!

        CarService
        ";

        $headers = "From: noreply@carservice.local";

        mail($email, $subject, $emailMessage, $headers);

        $selectedWeek = (new DateTime($appointmentDate))->modify('monday this week');

        header("Location: index.php?week=" . $selectedWeek->format('Y-m-d') . "&success=1");
        exit;
    } catch (PDOException $e) {
        $message = "Ez az időpont már foglalt, vagy hiba történt.";
    }
}

$stmt = $pdo->query("SELECT appointment_date FROM appointments");
$bookedAppointments = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (isset($_GET['week'])) {
    $weekStart = new DateTime($_GET['week']);
} else {
    $weekStart = new DateTime('monday this week');
}

$previousWeek = clone $weekStart;
$previousWeek->modify('-7 days');

$nextWeek = clone $weekStart;
$nextWeek->modify('+7 days');

$currentWeek = new DateTime('monday this week');

$days = [];

for ($i = 0; $i < 5; $i++) {
    $day = clone $weekStart;
    $day->modify("+$i day");
    $days[] = $day;
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>CarService időpontfoglalás</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Car Service - Időpontfoglalás</h1>
    <p class="subtitle">Gyors és egyszerű szervizidőpont-foglalás</p>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-message">Sikeres foglalás! Várjuk szeretettel!</div>
    <?php endif; ?>

    <div class="card">
        <div class="nav-links">
            <?php if ($previousWeek >= $currentWeek): ?>
                <a href="index.php?week=<?= $previousWeek->format('Y-m-d') ?>">
                    ← Előző hét
                </a>
            <?php endif; ?>

            <a href="index.php?week=<?= $nextWeek->format('Y-m-d') ?>">
                Következő hét →
            </a>
        </div>
        </p>

        <?php if (isset($_GET['success'])): ?>
            <p>Sikeres foglalás!</p>
        <?php endif; ?>

        <table border="1" cellpadding="10">
            <tr>
                <th>Időpont</th>

                <?php foreach ($days as $day): ?>
                    <th><?= $day->format('Y-m-d') ?></th>
                <?php endforeach; ?>
            </tr>

            <?php for ($hour = 8; $hour <= 17; $hour++): ?>
                <tr>
                    <td><?= $hour ?>:00</td>

                    <?php foreach ($days as $day): ?>
                        <?php
                        $slot = clone $day;
                        $slot->setTime($hour, 0, 0);

                        $slotString = $slot->format('Y-m-d H:i:s');
                        ?>

                        <?php if (in_array($slotString, $bookedAppointments)): ?>
                            <td class="booked">
                                Foglalt
                            </td>
                        <?php else: ?>
                            <td class="available">
                                <a href="index.php?week=<?= $weekStart->format('Y-m-d') ?>&appointment_date=<?= urlencode($slotString) ?>">
                                    Foglalható
                                </a>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endfor; ?>
        </table>

        <?php if (isset($_GET['appointment_date'])): ?>
            <div class="card booking-card">

                <h2>Időpont foglalása</h2>

                <p>
                    Kiválasztott időpont:
                    <strong><?= htmlspecialchars($_GET['appointment_date']) ?></strong>
                </p>

                <form method="POST">
                    <input
                        type="hidden"
                        name="appointment_date"
                        value="<?= htmlspecialchars($_GET['appointment_date']) ?>">

                    <input
                        type="text"
                        name="customer_name"
                        placeholder="Név"
                        required>

                    <input
                        type="tel"
                        name="phone"
                        placeholder="Telefonszám (pl. 06301234567)"
                        pattern="(\+36|06)[0-9]{9}"
                        title="A telefonszám formátuma például: 06301234567 vagy +36301234567"
                        required>

                    <input
                        type="email"
                        name="email"
                        placeholder="Email cím"
                        required>

                    <?php if (!empty($message)): ?>
                        <p class="error-message"><?= htmlspecialchars($message) ?></p>
                    <?php endif; ?>

                    <button type="submit">Foglalás mentése</button>
                </form>

            </div>
        <?php endif; ?>

    </div>

</body>

</html>