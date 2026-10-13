<?php
include 'db_connection.php';

// 获取分页参数
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;
$offset = ($page - 1) * $perPage;

// 获取搜索参数
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    // 构建基础查询
    $sql = "SELECT * FROM toys";
    $count_sql = "SELECT COUNT(*) as total FROM toys";
    $params = array();
    
    // 添加搜索条件
    if (!empty($search)) {
        $where = " WHERE name LIKE :search OR description LIKE :search";
        $sql .= $where;
        $count_sql .= $where;
        $params[':search'] = '%' . $search . '%';
    }
    
    // 添加分页限制
    $sql .= " LIMIT :offset, :perPage";
    
    // 获取玩具总数
    $stmt_count = $pdo->prepare($count_sql);
    if (!empty($search)) {
        $stmt_count->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    $stmt_count->execute();
    $totalToys = $stmt_count->fetch()['total'];
    
    // 计算总页数
    $totalPages = ceil($totalToys / $perPage);
    
    // 获取当前页的玩具
    $stmt = $pdo->prepare($sql);
    
    // 绑定搜索参数（如果有）
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    
    // 绑定分页参数
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    
    $stmt->execute();
    $toys = $stmt->fetchAll();
    
    if (count($toys) === 0) {
        if (!empty($search)) {
            echo '<div style="text-align: center; padding: 2rem;">';
            echo '<h3>Search Results</h3>';
            echo '<p>No toys found matching "' . htmlspecialchars($search) . '".</p>';
            echo '</div>';
        } else {
            echo '<div style="text-align: center; padding: 2rem;">';
            echo '<p>No toy data available.</p>';
            echo '</div>';
        }
        echo '<div id="pagination-data" data-total-pages="' . $totalPages . '" data-current-page="' . $page . '" style="display:none;"></div>';
        exit();
    }
    
    // 输出玩具卡片
    foreach ($toys as $toy) {
        echo '<div class="toy-card">';
        echo '<div class="toy-image">';
        
        // 改进的图片处理逻辑
        $imageUrl = $toy['image_url'];
        
        if (!empty($imageUrl)) {
            // 检查是否是有效的URL
            if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                // 如果是完整的URL，直接使用
                echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($toy['name']) . '" onerror="handleImageError(this)">';
            } else {
                // 如果是相对路径，构建正确的路径
                // 假设图片存储在项目根目录的images文件夹中
                $correctedPath = 'images/' . basename($imageUrl);
                
                // 检查文件是否存在
                if (file_exists($correctedPath)) {
                    echo '<img src="' . $correctedPath . '" alt="' . htmlspecialchars($toy['name']) . '" onerror="handleImageError(this)">';
                } else {
                    // 如果文件不存在，显示占位图
                    echo '<div class="image-placeholder">';
                    echo '<span>Image not found: ' . htmlspecialchars(basename($imageUrl)) . '</span>';
                    echo '</div>';
                }
            }
        } else {
            // 没有图片URL，显示占位图
            echo '<div class="image-placeholder">';
            echo '<span>No image available</span>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '<div class="toy-info">';
        echo '<h3>' . htmlspecialchars($toy['name']) . '</h3>';
        echo '<p>' . htmlspecialchars($toy['description']) . '</p>';
        echo '<div class="toy-price">$' . number_format($toy['price'], 2) . '</div>';
        echo '<button class="btn add-to-wishlist" data-toy-id="' . $toy['id'] . '">Add to Wishlist</button>';
        echo '</div>';
        echo '</div>';
    }
    
    // 输出分页信息
    echo '<div id="pagination-data" data-total-pages="' . $totalPages . '" data-current-page="' . $page . '" style="display:none;"></div>';
    
} catch (PDOException $e) {
    error_log("Database error in get_toys.php: " . $e->getMessage());
    echo '<div style="text-align: center; padding: 2rem; color: #d9534f;">';
    echo '<p>Error occurred while retrieving toy list.</p>';
    echo '</div>';
}
?>

<script>
// 图片加载错误处理函数
function handleImageError(img) {
    img.style.display = 'none';
    var placeholder = document.createElement('div');
    placeholder.className = 'image-placeholder';
    placeholder.innerHTML = '<span>Image failed to load</span>';
    img.parentNode.appendChild(placeholder);
}
</script>