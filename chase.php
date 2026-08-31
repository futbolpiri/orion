<?php
/**
 * SAE J661 Chase Test Advanced Analysis Panel
 * Auto-Calculating Wear Percentages Engine
 */

$analysis = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Metadata & Control Settings
    $test_name = $_POST['test_name'] ?? '';
    $grade     = $_POST['grade'] ?? '';
    $lot_no    = $_POST['lot_no'] ?? '';
    $part_no   = $_POST['part_no'] ?? '';
    
    $speed_rpm = floatval($_POST['speed_rpm'] ?? 417);
    $load_kgf  = floatval($_POST['load_kgf'] ?? 68);

    // 2. Wear Data (Mass & Thickness Measurements)
    $mass_initial  = floatval($_POST['mass_initial'] ?? 0);
    $mass_final    = floatval($_POST['mass_final'] ?? 0);
    $mass_loss     = floatval($_POST['mass_loss'] ?? 0);

    $thick_initial = floatval($_POST['thick_initial'] ?? 0);
    $thick_final   = floatval($_POST['thick_final'] ?? 0);
    $thick_loss    = floatval($_POST['thick_loss'] ?? 0);

    // Automatic Percentage Calculation Fix
    $mass_pct  = ($mass_initial > 0) ? ($mass_loss / $mass_initial) * 100 : floatval($_POST['wear_mass_pct'] ?? 0);
    $thick_pct = ($thick_initial > 0) ? ($thick_loss / $thick_initial) * 100 : floatval($_POST['wear_thick_pct'] ?? 0);

    // Wear Indicators
    $first_wear  = floatval($_POST['first_wear'] ?? 0);
    $second_wear = floatval($_POST['second_wear'] ?? 0);
    $third_wear  = floatval($_POST['third_wear'] ?? 0);

    // 3. Block Average Friction Coefficients (Average Mue)
    $b1_avg   = floatval($_POST['b1_avg'] ?? 0);
    $f1_avg   = floatval($_POST['f1_avg'] ?? 0);
    $r1_avg   = floatval($_POST['r1_avg'] ?? 0);
    $wear_avg = floatval($_POST['wear_avg'] ?? 0);
    $f2_avg   = floatval($_POST['f2_avg'] ?? 0);
    $r2_avg   = floatval($_POST['r2_avg'] ?? 0);
    $b2_avg   = floatval($_POST['b2_avg'] ?? 0);

    // 4. Official SAE Summary & Thermal Limits
    $normal_mu   = floatval($_POST['normal_mu'] ?? 0);
    $hot_mu      = floatval($_POST['hot_mu'] ?? 0);
    $f1_max_temp = floatval($_POST['f1_max_temp'] ?? 0);
    $f1_max_sec  = floatval($_POST['f1_max_sec'] ?? 0);
    $f2_max_temp = floatval($_POST['f2_max_temp'] ?? 0);
    $f2_max_sec  = floatval($_POST['f2_max_sec'] ?? 0);

    // Classifications
    $normal_code = get_j661_letter($normal_mu);
    $hot_code    = get_j661_letter($hot_mu);
    $rating_code = $normal_code . $hot_code;

    // Thermal Glazing & Degradation Detection
    $avg_wear_pct  = ($mass_pct + $thick_pct) / 2;
    $wear_drop_pct = (($b1_avg - $wear_avg) / max($b1_avg, 0.001)) * 100;
    $is_glazed     = ($wear_avg < 0.200 || $wear_drop_pct > 25.0);

    $wear_index           = calculate_advanced_wear_index($avg_wear_pct, $is_glazed);
    $total_indicator_wear = $first_wear + $second_wear + $third_wear;

    // Mileage Range Projections
    $work_factor = ($speed_rpm / 417) * ($load_kgf / 68);
    $km_ranges   = calculate_advanced_km_ranges($avg_wear_pct, $thick_loss, $work_factor, $is_glazed);

    $analysis = [
        'info' => [
            'test_name' => htmlspecialchars($test_name),
            'grade'     => htmlspecialchars($grade),
            'lot_no'    => htmlspecialchars($lot_no),
            'part_no'   => htmlspecialchars($part_no),
        ],
        'control' => [
            'speed' => $speed_rpm,
            'load'  => $load_kgf
        ],
        'rating' => [
            'code'        => $rating_code,
            'normal_code' => $normal_code,
            'hot_code'    => $hot_code,
            'normal_mu'   => number_format($normal_mu, 3),
            'hot_mu'      => number_format($hot_mu, 3),
        ],
        'wear' => [
            'mass_initial'  => number_format($mass_initial, 3),
            'mass_final'    => number_format($mass_final, 3),
            'mass_loss'     => number_format($mass_loss, 3),
            'mass_pct'      => number_format($mass_pct, 3),
            'thick_initial' => number_format($thick_initial, 3),
            'thick_final'   => number_format($thick_final, 3),
            'thick_loss'    => number_format($thick_loss, 3),
            'thick_pct'     => number_format($thick_pct, 3),
            'first_wear'    => number_format($first_wear, 2),
            'second_wear'   => number_format($second_wear, 2),
            'third_wear'    => number_format($third_wear, 2),
            'total_ind'     => number_format($total_indicator_wear, 2),
            'index'         => $wear_index
        ],
        'blocks' => [
            'b1' => number_format($b1_avg, 3), 'f1' => number_format($f1_avg, 3),
            'r1' => number_format($r1_avg, 3), 'wear' => number_format($wear_avg, 3),
            'f2' => number_format($f2_avg, 3), 'r2' => number_format($r2_avg, 3),
            'b2' => number_format($b2_avg, 3)
        ],
        'thermal' => [
            'f1_temp' => $f1_max_temp,
            'f1_sec'  => $f1_max_sec,
            'f2_temp' => $f2_max_temp,
            'f2_sec'  => $f2_max_sec
        ],
        'km' => $km_ranges,
        'notes' => generate_full_insights($b1_avg, $wear_avg, $wear_drop_pct, $avg_wear_pct, $third_wear, $is_glazed, $f1_max_temp, $f1_max_sec, $f2_max_temp, $f2_max_sec)
    ];
}

function get_j661_letter($mu) {
    if ($mu < 0.15) return 'C';
    if ($mu < 0.25) return 'D';
    if ($mu < 0.35) return 'E';
    if ($mu < 0.45) return 'F';
    if ($mu < 0.55) return 'G';
    return 'H';
}

function calculate_advanced_wear_index($avg_wear, $is_glazed) {
    if ($is_glazed) {
        return ['label' => 'Severe Thermal Glazing (Surface Burning)', 'class' => 'status-danger'];
    }
    if ($avg_wear < 3.5) {
        return ['label' => 'High Resistance (Hard Compound)', 'class' => 'status-good'];
    } elseif ($avg_wear <= 6.0) {
        return ['label' => 'Moderate Resistance (Balanced Compound)', 'class' => 'status-info'];
    } else {
        return ['label' => 'Low Resistance (Soft / Comfort-Oriented)', 'class' => 'status-warn'];
    }
}

function calculate_advanced_km_ranges($avg_wear_pct, $thick_loss_mm, $work_factor, $is_glazed) {
    if ($is_glazed) {
        return [
            'city'    => 'UNSTABLE / NOT RECOMMENDED',
            'mixed'   => 'UNSTABLE / NOT RECOMMENDED',
            'highway' => 'UNSTABLE / NOT RECOMMENDED'
        ];
    }

    $wear_factor = (3.0 / max($avg_wear_pct, 0.5)) * (0.350 / max($thick_loss_mm, 0.05)) * max($work_factor, 0.1);
    
    $base_min = 35000 * sqrt($wear_factor);
    $base_max = 50000 * sqrt($wear_factor);

    $mixed_min = min(max(round($base_min / 5000) * 5000, 15000), 65000);
    $mixed_max = min(max(round($base_max / 5000) * 5000, 25000), 90000);

    return [
        'city'    => number_format(round($mixed_min * 0.65 / 1000) * 1000, 0, ',', '.') . ' - ' . number_format(round($mixed_max * 0.70 / 1000) * 1000, 0, ',', '.') . ' KM',
        'mixed'   => number_format($mixed_min, 0, ',', '.') . ' - ' . number_format($mixed_max, 0, ',', '.') . ' KM',
        'highway' => number_format(round($mixed_min * 1.35 / 1000) * 1000, 0, ',', '.') . ' - ' . number_format(round($mixed_max * 1.45 / 1000) * 1000, 0, ',', '.') . ' KM',
    ];
}

function generate_full_insights($b1_avg, $wear_avg, $wear_drop_pct, $avg_wear, $third_wear, $is_glazed, $f1_temp, $f1_sec, $f2_temp, $f2_sec) {
    $notes = [];
    
    if ($is_glazed) {
        $notes[] = "<b style='color:#dc2626;'>CRITICAL WARNING - Thermal Glazing & Resin Degradation:</b> Friction collapsed to <b>{$wear_avg}</b> during the Wear Block (" . number_format($wear_drop_pct, 1) . "% collapse). The material slipped and experienced severe organic binder burning (blackened surface). Low wear loss is misleading due to surface glazing.";
    }

    $notes[] = "<b>Fade-1 Limit:</b> Max temperature of <b>{$f1_temp} °C</b> was reached at <b>{$f1_sec} seconds</b>.";
    $notes[] = "<b>Fade-2 Limit:</b> Max temperature of <b>{$f2_temp} °C</b> was reached at <b>{$f2_sec} seconds</b>.";

    if ($third_wear >= 0.30) {
        $notes[] = "<b style='color:#b45309;'>HIGH THERMAL MATRIX WEAR:</b> Third Wear reached <b>{$third_wear} mm</b>, indicating progressive structural breakup under continuous thermal strain.";
    }

    if (!$is_glazed) {
        if ($avg_wear > 6.0) {
            $notes[] = "<b>Soft Compound Structure:</b> Average wear loss is " . number_format($avg_wear, 2) . "%. Offers quiet operation and rotor-friendly performance.";
        } elseif ($avg_wear < 3.5) {
            $notes[] = "<b>Hard / Long-Life Structure:</b> Average wear loss is " . number_format($avg_wear, 2) . "%. Friction material is engineered for durability.";
        } else {
            $notes[] = "<b>Balanced Compound Structure:</b> Average wear loss is " . number_format($avg_wear, 2) . "%. Standard wear behavior under lab conditions.";
        }
    }

    return $notes;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAE J661 Technical Analysis Panel</title>
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --primary: #2563eb;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body { font-family: system-ui, -apple-system, sans-serif; background: var(--bg-color); color: var(--text-main); padding: 24px 16px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; }
        
        .form-section { margin-bottom: 20px; }
        .section-header { font-size: 0.9rem; font-weight: 700; text-transform: uppercase; color: var(--primary); margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 4px; }
        .form-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        
        label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px; }
        input { width: 100%; padding: 8px 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; }
        button { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 10px; }
        
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; }
        .status-good { background: #dcfce7; color: #15803d; }
        .status-info { background: #e0f2fe; color: #0369a1; }
        .status-warn { background: #fef3c7; color: #b45309; }
        .status-danger { background: #fee2e2; color: #991b1b; }

        .metrics-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin: 16px 0; }
        .metric-box { background: #f8fafc; border: 1px solid #f1f5f9; padding: 14px; border-radius: 8px; text-align: center; }
        .metric-val { font-size: 1.4rem; font-weight: 800; color: var(--primary); }
        .metric-lbl { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-top: 4px; }

        table.data-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-top: 10px; }
        table.data-table th, table.data-table td { padding: 8px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        table.data-table th { color: var(--text-muted); font-weight: 600; }

        .disclaimer-box { background: #f1f5f9; border-left: 4px solid #64748b; padding: 12px 16px; font-size: 0.8rem; color: #475569; margin-top: 20px; border-radius: 0 6px 6px 0; }
        ul.km-list { list-style: none; padding: 0; }
        ul.km-list li { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed var(--border-color); font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-title">SAE J661 Technical Report Input Form</div>
        <form method="POST">
            
            <!-- 1. Metadata & Control Settings -->
            <div class="form-section">
                <div class="section-header">1. Report & Control Settings</div>
                <div class="form-grid-4">
                    <div><label>Test Name/No:</label><input type="text" name="test_name" value="502"></div>
                    <div><label>Grade/Compound:</label><input type="text" name="grade" value="ORION-BLUE"></div>
                    <div><label>Speed (rpm):</label><input type="text" name="speed_rpm" value="417"></div>
                    <div><label>Load (Kgf):</label><input type="text" name="load_kgf" value="68"></div>
                </div>
            </div>

            <!-- 2. Wear Data (Mass & Thickness) -->
            <div class="form-section">
                <div class="section-header">2. Mass & Thickness Wear Measurements</div>
                <div class="form-grid-4">
                    <div><label>Initial Mass (gm):</label><input type="text" name="mass_initial" value="8.100"></div>
                    <div><label>Final Mass (gm):</label><input type="text" name="mass_final" value="7.540"></div>
                    <div><label>Mass Loss (gm):</label><input type="text" name="mass_loss" value="0.560"></div>
                    <div><label>Mass Wear (%):</label><input type="text" name="wear_mass_pct" value=""></div>

                    <div><label>Initial Thick (mm):</label><input type="text" name="thick_initial" value="5.603"></div>
                    <div><label>Final Thick (mm):</label><input type="text" name="thick_final" value="5.198"></div>
                    <div><label>Thick Loss (mm):</label><input type="text" name="thick_loss" value="0.405"></div>
                    <div><label>Thick Wear (%):</label><input type="text" name="wear_thick_pct" value=""></div>
                </div>
            </div>

            <!-- 3. Wear Indicators -->
            <div class="form-section">
                <div class="section-header">3. Wear Indicator Steps (mm)</div>
                <div class="form-grid-4">
                    <div><label>First Wear (mm):</label><input type="text" name="first_wear" value="0.05"></div>
                    <div><label>Second Wear (mm):</label><input type="text" name="second_wear" value="0.36"></div>
                    <div><label>Third Wear (mm):</label><input type="text" name="third_wear" value="0.41"></div>
                    <div><label>-</label></div>
                </div>
            </div>

            <!-- 4. Block Average Mue -->
            <div class="form-section">
                <div class="section-header">4. Test Block Average Friction Coefficients (Average Mue)</div>
                <div class="form-grid-4">
                    <div><label>Baseline-1 Avg:</label><input type="text" name="b1_avg" value="0.353"></div>
                    <div><label>Fade-1 Avg:</label><input type="text" name="f1_avg" value="0.321"></div>
                    <div><label>Recovery-1 Avg:</label><input type="text" name="r1_avg" value="0.338"></div>
                    <div><label>Wear Block Avg:</label><input type="text" name="wear_avg" value="0.344"></div>
                    <div><label>Fade-2 Avg:</label><input type="text" name="f2_avg" value="0.337"></div>
                    <div><label>Recovery-2 Avg:</label><input type="text" name="r2_avg" value="0.306"></div>
                    <div><label>Baseline-2 Avg:</label><input type="text" name="b2_avg" value="0.359"></div>
                    <div><label>-</label></div>
                </div>
            </div>

            <!-- 5. Official Summary & Thermal Limits -->
            <div class="form-section">
                <div class="section-header">5. SAE J661 Official Summary & Thermal Timings</div>
                <div class="form-grid-4">
                    <div><label>Normal Mue-N:</label><input type="text" name="normal_mu" value="0.343"></div>
                    <div><label>Hot Mue -H:</label><input type="text" name="hot_mu" value="0.321"></div>
                    <div><label>Fade-1 Max Temp (°C):</label><input type="text" name="f1_max_temp" value="289"></div>
                    <div><label>Fade-1 Time (sec):</label><input type="text" name="f1_max_sec" value="346"></div>
                    <div><label>Fade-2 Max Temp (°C):</label><input type="text" name="f2_max_temp" value="346"></div>
                    <div><label>Fade-2 Time (sec):</label><input type="text" name="f2_max_sec" value="433"></div>
                </div>
            </div>

            <button type="submit">Execute Advanced Technical Analysis</button>
        </form>
    </div>

    <?php if ($analysis): ?>
        <div class="card">
            <div class="card-title">SAE J661 Technical Report: <?= $analysis['info']['test_name'] ?> (<?= $analysis['info']['grade'] ?>)</div>
            
            <div class="metrics-grid">
                <div class="metric-box">
                    <div class="metric-val"><?= $analysis['rating']['code'] ?></div>
                    <div class="metric-lbl">SAE J661 Class</div>
                </div>
                <div class="metric-box">
                    <div class="metric-val"><?= $analysis['rating']['normal_mu'] ?></div>
                    <div class="metric-lbl">Normal Mue-N</div>
                </div>
                <div class="metric-box">
                    <div class="metric-val"><?= $analysis['rating']['hot_mu'] ?></div>
                    <div class="metric-lbl">Hot Mue -H</div>
                </div>
                <div class="metric-box">
                    <div style="margin-top:4px;"><span class="status-badge <?= $analysis['wear']['index']['class'] ?>"><?= $analysis['wear']['index']['label'] ?></span></div>
                    <div class="metric-lbl" style="margin-top:10px;">Wear Index</div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:20px;">
                <div>
                    <div class="section-header">Block Friction Matrix</div>
                    <table class="data-table">
                        <thead>
                            <tr><th>Test Block</th><th>Average Mue ($\mu$)</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Baseline-1 (Run-In)</td><td><?= $analysis['blocks']['b1'] ?></td></tr>
                            <tr><td>Fade-1 (Thermal Load)</td><td><?= $analysis['blocks']['f1'] ?></td></tr>
                            <tr><td>Recovery-1 (Cooling 1)</td><td><?= $analysis['blocks']['r1'] ?></td></tr>
                            <tr><td>Wear Block (100 Apps)</td><td><?= $analysis['blocks']['wear'] ?></td></tr>
                            <tr><td>Fade-2 (Heavy Load)</td><td><?= $analysis['blocks']['f2'] ?></td></tr>
                            <tr><td>Recovery-2 (Cooling 2)</td><td><?= $analysis['blocks']['r2'] ?></td></tr>
                            <tr><td>Baseline-2 (Final)</td><td><?= $analysis['blocks']['b2'] ?></td></tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <div class="section-header">Wear & Dimensional Data</div>
                    <table class="data-table">
                        <thead>
                            <tr><th>Parameter</th><th>Initial</th><th>Final</th><th>Loss / Value</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mass (gm)</td>
                                <td><?= $analysis['wear']['mass_initial'] ?></td>
                                <td><?= $analysis['wear']['mass_final'] ?></td>
                                <td><?= $analysis['wear']['mass_loss'] ?> gm (%<?= $analysis['wear']['mass_pct'] ?>)</td>
                            </tr>
                            <tr>
                                <td>Thickness (mm)</td>
                                <td><?= $analysis['wear']['thick_initial'] ?></td>
                                <td><?= $analysis['wear']['thick_final'] ?></td>
                                <td><?= $analysis['wear']['thick_loss'] ?> mm (%<?= $analysis['wear']['thick_pct'] ?>)</td>
                            </tr>
                            <tr>
                                <td colspan="3">First Wear Indicator</td>
                                <td><?= $analysis['wear']['first_wear'] ?> mm</td>
                            </tr>
                            <tr>
                                <td colspan="3">Second Wear Indicator</td>
                                <td><?= $analysis['wear']['second_wear'] ?> mm</td>
                            </tr>
                            <tr>
                                <td colspan="3">Third Wear Indicator</td>
                                <td><?= $analysis['wear']['third_wear'] ?> mm</td>
                            </tr>
                            <tr>
                                <td colspan="3"><b>Total Wear Indicator</b></td>
                                <td><b><?= $analysis['wear']['total_ind'] ?> mm</b></td>
                            </tr>
                            <tr>
                                <td colspan="3"><b>Fade-1 Max Temp & Time</b></td>
                                <td><b><?= $analysis['thermal']['f1_temp'] ?> °C / <?= $analysis['thermal']['f1_sec'] ?> sec</b></td>
                            </tr>
                            <tr>
                                <td colspan="3"><b>Fade-2 Max Temp & Time</b></td>
                                <td><b><?= $analysis['thermal']['f2_temp'] ?> °C / <?= $analysis['thermal']['f2_sec'] ?> sec</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-top:20px;">
                <div class="section-header">Estimated Mileage Projections</div>
                <ul class="km-list">
                    <li><span>Urban Driving (Stop-and-Go):</span> <strong><?= $analysis['km']['city'] ?></strong></li>
                    <li><span>Mixed Driving (Standard Duty):</span> <strong><?= $analysis['km']['mixed'] ?></strong></li>
                    <li><span>Highway / Long Distance:</span> <strong><?= $analysis['km']['highway'] ?></strong></li>
                </ul>
            </div>

            <div style="margin-top: 20px;">
                <div class="section-header">Engineering Insights & Critical Diagnosis</div>
                <ul>
                    <?php foreach ($analysis['notes'] as $note): ?>
                        <li style="margin-bottom: 6px; font-size: 0.9rem;"><?= $note ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="disclaimer-box">
                <strong>Technical Disclaimer:</strong> SAE J661 test data is derived under controlled laboratory conditions. Operational mileage projections are invalidated if severe thermal glazing or friction collapse is detected.
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>