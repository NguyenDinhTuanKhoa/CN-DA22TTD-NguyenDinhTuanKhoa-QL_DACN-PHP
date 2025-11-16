<?php
echo "<h2>🔍 SIMPLE TEST</h2>";

// Test 1: Config
echo "<h3>1. Test Config:</h3>";
try {
    require_once 'config/config.php';
    echo "✅ Config loaded<br>";
    echo "DB_HOST: " . DB_HOST . "<br>";
    echo "DB_NAME: " . DB_NAME . "<br>";
    echo "DB_USER: " . DB_USER . "<br>";
} catch (Exception $e) {
    echo "❌ Config error: " . $e->getMessage() . "<br>";
}

// Test 2: Database
echo "<h3>2. Test Database:</h3>";
try {
    require_once 'app/core/Database.php';
    $db = Database::getInstance();
    echo "✅ Database connected<br>";
    
    // Test query
    $stmt = $db->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total users: " . $result['total'] . "<br>";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 3: User Model
echo "<h3>3. Test User Model:</h3>";
try {
    require_once 'app/models/User.php';
    $userModel = new User();
    echo "✅ User model created<br>";
    
    // Test create với dữ liệu đơn giản
    $testData = [
        'username' => 'simple_test_' . time(),
        'password' => '123456',
        'full_name' => 'Simple Test',
        'email' => 'simple_' . time() . '@test.com',
        'role' => 'student',
        'student_code' => 'SIMPLE' . time(),
        'phone' => '0123456789'
    ];
    
    echo "Test data:<br>";
    echo "<pre>";
    print_r($testData);
    echo "</pre>";
    
    $result = $userModel->create($testData);
    echo "Create result: " . ($result ? "✅ SUCCESS" : "❌ FAILED") . "<br>";
    
    if ($result) {
        // Verify in database
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$testData['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "✅ User verified in database<br>";
        } else {
            echo "❌ User NOT found in database<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ User model error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}