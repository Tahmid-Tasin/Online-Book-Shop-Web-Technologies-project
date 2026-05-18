<?php include 'admin_nav.php'; ?>

<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
    }
    .stat-card {
        background: #FFFDF8;
        border: 1px solid #e2ddcf;
        border-radius: 10px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        border-color: #B7E778;
    }
    .stat-card h3 {
        margin: 0 0 10px 0;
        color: #7A5C3E;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-card h2 {
        margin: 0;
        color: #1F3B2D;
        font-size: 32px;
        font-weight: bold;
    }
</style>

<div class="page-header" style="text-align: center; margin-top: 30px;">
    <h1 style="color: #1F3B2D; margin-bottom: 5px;">Admin Dashboard</h1>
    <p style="color: #7A5C3E; margin-top: 0;">Real-time overview of bookstore activities.</p>
</div>

<div class="dashboard-container">
    <div class="stat-card">
        <h3>Total Books</h3>
        <h2><?php echo intval($stats['books']); ?></h2>
    </div>
    
    <div class="stat-card">
        <h3>Total Customers</h3>
        <h2><?php echo intval($stats['users']); ?></h2>
    </div>
    
    <div class="stat-card">
        <h3>Total Orders</h3>
        <h2><?php echo intval($stats['orders']); ?></h2>
    </div>
    
    <div class="stat-card" style="border-left: 4px solid #B7E778;">
        <h3>Total Revenue</h3>
        <h2><?php echo number_format((float)$stats['revenue'], 2); ?> <span style="font-size: 18px;">Tk</span></h2>
    </div>
</div>

</body>
</html>