<?php
// Simple verifier to check which desa are missing vs expected list (139 desa, 16 kecamatan)
// Access: /tools/check_missing_desa.php?token=YOUR_TOKEN
// Security: requires DB_CHECK_TOKEN env variable to match ?token

require_once __DIR__ . '/../includes/db.php';

header('Content-Type: text/plain; charset=utf-8');
$expectedToken = getenv('DB_CHECK_TOKEN') ?: 'TDSEKALI';
$token = $_GET['token'] ?? '';
if ($token !== $expectedToken) {
    http_response_code(403);
    echo "Forbidden. Provide correct token.\n";
    exit;
}

$expected = [
    'Ujung Batu' => [
        'Suka Damai','Ngaso','Ujung Batu Timur','Pematang Tebih'
    ],
    'Rokan IV Koto' => [
        'Cipang Kanan','Cipang Kiri Hulu','Cipang Kiri Hilir','Tanjung Medan','Lubuk Bendahara Timur','Lubuk Bendahara','Sikebau Jaya','Rokan Koto Ruang','Rokan Timur','Lubuk Betung','Pemandang','Alahan','Tibawan'
    ],
    'Rambah' => [
        'Rambah Tengah Utara','Rambah Tengah Hilir','Rambah Tengah Hulu','Rambah Tengah Barat','Menaming','Pasir Baru','Sialang Jaya','Tanjung Belit','Koto Tinggi','Suka Maju','Pematang Berangan','Babussalam','Pasir Maju'
    ],
    'Tambusai' => [
        'Tambusai Barat','Tambusai Timur','Batas','Talikumain','Rantau Panjang','Sungai Kumango','Batang Kumu','Sialang Rindang','Suka Maju','Lubuk Soting','Tingkok'
    ],
    'Kepenuhan' => [
        'Kepenuhan Barat','Kepenuhan Hilir','Kepenuhan Timur','Sei Rokan Jaya','Kepenuhan Raya','Kepenuhan Baru','Kepenuhan Barat Mulya','Ulak Patian','Rantau Binuang Sakti'
    ],
    'Kunto Darussalam' => [
        'Kota Intan','Muara Dilam','Kota Raya','Kota Baru','Sungai Kuti','Pasir Indah','Pasir Luhur','Bukit Intan Makmur','Bagan Tujuh'
    ],
    'Rambah Samo' => [
        'Rambah Samo','Rambah Samo Barat','Rambah Baru','Rambah Utama','Pasir Makmur','Karya Mulya','Marga Mulya','Langkitin','Masda Makmur','Lubuk Napal','Teluk Aur','Sei Salak','Sei Kuning','Lubuk Bilang'
    ],
    'Rambah Hilir' => [
        'Rambah Hilir','Rambah Hilir Tengah','Rambah Hilir Timur','Pasir Utama','Pasir Jaya','Rambah Muda','Sungai Sitolang','Lubuk Kerapat','Rambah','Serombou Indah','Sungai Dua Indah','Muara Musu','Sejati'
    ],
    'Tambusai Utara' => [
        'Tambusai Utara','Mahato','Bangun Jaya','Simpang Harapan','Pagar Mayang','Payung Sekaki','Mekar Jaya','Tanjung Medan','Suka Damai','Rantau Sakti','Mahato Sakti'
    ],
    'Bangun Purba' => [
        'Pasir Agung','Pasir Intan','Rambah Jaya','Bangun Purba','Bangun Purba Timur Jaya','Bangun Purba Barat','Tangun'
    ],
    'Tandun' => [
        'Tandun','Kumain','Bono Tapung','Dayo','Tapung Jaya','Puo Raya','Sei Kuning','Koto Tandun','Tandun Barat'
    ],
    'Kabun' => [
        'Kabun','Aliantan','Kota Ranah','Boncah Kesuma','Batu Langkah Besar','Giti'
    ],
    'Bonai Darussalam' => [
        'Teluk Sono','Sontang','Bonai','Rawa Makmur','Pauh','Kasang Padang','Kasang Mungkal'
    ],
    'Pagaran Tapah Darussalam' => [
        'Pagaran Tapah','Kembang Damai','Sangkir Indah'
    ],
    'Kepenuhan Hulu' => [
        'Kepenuhan Hulu','Pekan Tebih','Kepayang','Muara Jaya','Kepenuhan Jaya'
    ],
    'Pendalian IV Koto' => [
        'Pendalian','Bengkolan Salak','Suligi','Air Panas','Sei Kandis'
    ],
];

// Load actual from DB
$actual = [];
$res = $conn->query("SELECT kecamatan, nama_desa FROM desa");
if (!$res) {
    echo 'DB error: ' . $conn->error . "\n";
    exit;
}
while ($row = $res->fetch_assoc()) {
    $kec = $row['kecamatan'];
    $desa = $row['nama_desa'];
    $actual[$kec][$desa] = true;
}

$missing = [];
$extra = [];
$expectedTotal = 0;
$actualTotal = 0;
foreach ($expected as $kec => $list) {
    $expectedTotal += count($list);
    $actList = isset($actual[$kec]) ? array_keys($actual[$kec]) : [];
    $actualTotal += count($actList);
    foreach ($list as $desa) {
        if (!isset($actual[$kec][$desa])) {
            $missing[$kec][] = $desa;
        }
    }
}
// extras: entries in DB that not in expected list
foreach ($actual as $kec => $map) {
    foreach (array_keys($map) as $desa) {
        if (!isset($expected[$kec]) || !in_array($desa, $expected[$kec], true)) {
            $extra[$kec][] = $desa;
        }
    }
}

// Output summary
echo "EXPECTED TOTAL DESA: $expectedTotal\n";
$q = $conn->query("SELECT COUNT(*) AS c FROM desa");
$cnt = $q ? (int)$q->fetch_assoc()['c'] : 0;
echo "ACTUAL TOTAL DESA:   $cnt\n\n";

if (!empty($missing)) {
    echo "MISSING (expected but not found):\n";
    foreach ($missing as $kec => $list) {
        foreach ($list as $d) {
            echo "- [$kec] $d\n";
        }
    }
    echo "\n";
} else {
    echo "No missing entries.\n\n";
}

if (!empty($extra)) {
    echo "EXTRA (found but not in expected list):\n";
    foreach ($extra as $kec => $list) {
        foreach ($list as $d) {
            echo "- [$kec] $d\n";
        }
    }
    echo "\n";
}

// Per-kecamatan counts vs expected
echo "COUNTS PER KECAMATAN (actual/expected):\n";
foreach ($expected as $kec => $list) {
    $actCount = isset($actual[$kec]) ? count($actual[$kec]) : 0;
    echo sprintf("- %s: %d/%d\n", $kec, $actCount, count($list));
}

// Quick typo hints
echo "\nCHECK COMMON TYPO CANDIDATES:\n";
$hints = [
    ["Ramban","Rambah Hilir"],
    ["Manato","Tambusai Utara"],
    ["Manato Sakti","Tambusai Utara"],
    ["Bangun Purba","Bangun Purbaa"],
];
foreach ($hints as [$desa,$kec]) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM desa WHERE nama_desa=? AND kecamatan=?");
    $stmt->bind_param('ss',$desa,$kec);
    $stmt->execute();
    $stmt->bind_result($c); $stmt->fetch(); $stmt->close();
    echo sprintf("- %s @ %s: %d\n", $desa, $kec, (int)$c);
}
