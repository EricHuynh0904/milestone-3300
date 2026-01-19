<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Todo List DB Interface</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-dark:      #353535;   /* Darkest background */
      --bg-card:      #ffffff;   /* Foreground cards */
      --accent-blue:  #3c6e71;   /* Button/link accent */
      --accent-blue2: #284b63;   /* Nav hover accent */
      --text-light:   #ffffff;   /* White text */
      --text-dark:    #353535;   /* Dark text for cards */
      --grey-soft:    #d9d9d9;   /* Muted grey */

      --radius:       12px;
      --shadow:       0 12px 22px rgba(0,0,0,0.35);
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      padding: 0;
      background: var(--bg-dark);
      font-family: "Inter", sans-serif;
      color: var(--text-light);
    }

    a {
      color: var(--text-light);
      text-decoration: none;
    }
    a:hover {
      opacity: 0.85;
    }

    header {
      position: sticky;
      top: 0;
      background: rgba(53,53,53,0.95);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid #222;
      z-index: 100;
    }

    .topbar-inner {
      max-width: 1100px;
      margin: 0 auto;
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .brand-icon {
      width: 32px;
      height: 32px;
      background: var(--accent-blue);
      color: white;
      border-radius: 8px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: 700;
      box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    }

    .brand-text-main {
      font-size: 18px;
      font-weight: 600;
      color: var(--text-light);
    }

    .brand-text-sub {
      font-size: 12px;
      color: var(--grey-soft);
    }

    nav {
      display: flex;
      gap: 10px;
    }

    nav a {
      padding: 6px 14px;
      font-size: 14px;
      border-radius: 999px;
      transition: 0.15s ease;
      color: white !important;
      border: 1px solid transparent;
    }

    nav a:hover {
      background: var(--accent-blue2);
      border-color: var(--accent-blue);
    }

    nav a.active {
      background: var(--accent-blue);
      color: #ffffff !important;
    }

    .container {
      max-width: 1100px;
      margin: 26px auto 40px;
      padding: 0 20px;
    }

    
    .card {
      background: var(--bg-card);
      border-radius: var(--radius);
      border: 1px solid var(--grey-soft);
      box-shadow: var(--shadow);
      padding: 20px;
      margin-bottom: 24px;
      color: var(--text-dark);
    }

    .card-header {
      margin-bottom: 10px;
    }

    .card h2 {
      margin: 0;
      font-size: 20px;
      font-weight: 600;
      color: var(--accent-blue);
    }

    .card ul li a {
    color: var(--accent-blue2) !important;
    font-weight: 600;
    }

    .muted {
      font-size: 13px;
      color: #666;
    }
    /* LISTS */
    ol {
      margin-left: 20px;
    }

    ol li {
      margin-bottom: 6px;
    }

    ol li a {
      color: var(--accent-blue2) !important;
      font-weight: 600;
    }
    ol li a:hover {
      text-decoration: underline;
    }

    /* BUTTONS */
    .btn, button, input[type=submit] {
      padding: 8px 16px;
      background: var(--accent-blue);
      color: #ffffff;
      border: none;
      border-radius: 999px;
      cursor: pointer;
      font-size: 14px;
      transition: 0.15s ease;
    }
    .btn:hover, button:hover, input[type=submit]:hover {
      background: var(--accent-blue2);
    }

    /* TABLES */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
      background: var(--bg-card);
      border: 1px solid var(--grey-soft);
      border-radius: 10px;
      overflow: hidden;
      color: var(--text-dark);
    }

    th {
      background: var(--accent-blue);
      padding: 10px;
      color: white;
      text-align: left;
    }

    td {
      padding: 10px;
      border-bottom: 1px solid var(--grey-soft);
    }

    tr:hover td {
      background: var(--accent-blue2);
      color: white;
    }

    /* TEXT AREA */
    textarea {
      width: 100%;
      height: 150px;
      border-radius: 10px;
      background: var(--d9);
      background: #eeeeee;
      border: 1px solid var(--grey-soft);
      padding: 10px;
      font-family: monospace;
      color: var(--text-dark);
      font-size: 14px;
    }

    textarea:focus {
      outline: none;
      border-color: var(--accent-blue);
    }

    th.sortable {
      cursor: pointer;
    }

    th.sortable::after {
      content: ' ><';
      font-size: 11px;
      color: var(--grey-soft);
    }

    th.sortable[data-dir="asc"]::after {
      content: ' ▲';
    }

    th.sortable[data-dir="desc"]::after {
      content: ' ▼';
    }

  </style>
</head>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // add a click handler to every variable
  document.querySelectorAll('th.sortable').forEach(function (th) {
    th.addEventListener('click', function () {
      const table = th.closest('table');
      const tbody = table.tBodies[0];
      const rows = Array.from(tbody.querySelectorAll('tr'));

      const colIndex = Array.from(th.parentNode.children).indexOf(th);

      const currentDir = th.getAttribute('data-dir') === 'asc' ? 'asc' : 'desc';
      const newDir = currentDir === 'asc' ? 'desc' : 'asc';
      th.setAttribute('data-dir', newDir);

      
      th.parentNode.querySelectorAll('th.sortable').forEach(function (other) {
        if (other !== th) other.removeAttribute('data-dir');
      });

      rows.sort(function (rowA, rowB) {
        const cellA = rowA.children[colIndex].innerText.trim();
        const cellB = rowB.children[colIndex].innerText.trim();

        const numA = parseFloat(cellA);
        const numB = parseFloat(cellB);
        const bothNumeric = !isNaN(numA) && !isNaN(numB);

        if (bothNumeric) {
          return newDir === 'asc' ? numA - numB : numB - numA;
        } else {
          return newDir === 'asc'
            ? cellA.localeCompare(cellB)
            : cellB.localeCompare(cellA);
        }
      });

      // re attach rows in new order
      rows.forEach(function (row) {
        tbody.appendChild(row);
      });
    });
  });
});
</script>

<body>
<header>
  <div class="topbar-inner">
    <div class="brand">
      <div class="brand-icon">✓</div>
      <div>
        <div class="brand-text-main">Todo List Database</div>
        <div class="brand-text-sub">CPSC 3300 </div>
      </div>
    </div>

  <nav>
    <a href="index.php"     class="<?php echo $current === 'index.php' ? 'active' : ''; ?>">Home</a>
    <a href="relations.php" class="<?php echo $current === 'relations.php' ? 'active' : ''; ?>">Relations</a>
    <a href="queries.php"   class="<?php echo $current === 'queries.php' ? 'active' : ''; ?>">Queries</a>
    <a href="adhoc.php"     class="<?php echo $current === 'adhoc.php' ? 'active' : ''; ?>">Ad-hoc</a>
    <a href="signup.php"    class="<?php echo $current === 'signup.php' ? 'active' : ''; ?>">Sign Up</a>
  </nav>

  </div>
</header>

<div class="container">
