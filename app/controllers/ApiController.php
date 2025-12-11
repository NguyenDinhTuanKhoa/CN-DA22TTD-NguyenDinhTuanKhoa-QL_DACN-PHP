<?php
class ApiController extends Controller {
    
    public function index() {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'API endpoint not found']);
        exit;
    }
    
    public function chatbot() {
        // Set JSON header
        header('Content-Type: application/json; charset=utf-8');
        
        // Check session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            return;
        }
        
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $message = $input['message'] ?? '';
        
        if (empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Empty message']);
            return;
        }
        
        // Process message and get response
        $response = $this->processMessage($message);
        
        echo json_encode([
            'success' => true,
            'response' => $response
        ]);
    }
    
    private function processMessage($message) {
        $messageLower = mb_strtolower($message, 'UTF-8');
        $db = Database::getInstance();
        
        // ========== GỢI Ý ĐỀ TÀI (ƯU TIÊN CAO NHẤT - CHUYỂN CHO AI) ==========
        if ($this->containsKeywords($messageLower, ['gợi ý đề tài', 'gợi ý topic', 'đề xuất đề tài', 'suggest topic', 'ý tưởng đề tài', 'đề tài về'])) {
            if (defined('GEMINI_API_KEY') && !empty(GEMINI_API_KEY)) {
                return $this->askGemini($message, $db);
            }
            return $this->getTopicSuggestionFallback();
        }
        
        // ========== TÌM KIẾM NHANH BẰNG MÃ SỐ ==========
        // Nếu message chứa số có 6-12 chữ số, tự động tìm kiếm sinh viên
        if (preg_match('/\b(\d{6,12})\b/', $message, $matches)) {
            if ($this->containsKeywords($messageLower, ['tìm', 'mã', 'sinh viên', 'sv', 'mssv', 'số'])) {
                return $this->searchStudents($db, $messageLower);
            }
        }
        
        // ========== THỐNG KÊ TỔNG QUAN (chỉ khi có từ "thống kê" rõ ràng) ==========
        if ($this->containsKeywords($messageLower, ['thống kê tổng', 'tổng quan', 'dashboard', 'báo cáo tổng'])) {
            return $this->getOverviewStats($db);
        }
        
        // ========== SINH VIÊN CỦA TÔI (cho giáo viên) ==========
        if ($this->containsKeywords($messageLower, ['sinh viên của tôi', 'sv của tôi', 'tôi hướng dẫn', 'tôi đang hướng dẫn', 'sinh viên tôi', 'học sinh của tôi'])) {
            return $this->getMyStudents($db);
        }
        
        // ========== SINH VIÊN ==========
        if ($this->containsKeywords($messageLower, ['sinh viên', 'student', 'sv', 'học sinh', 'mssv'])) {
            if ($this->containsKeywords($messageLower, ['tìm', 'tên', 'mssv', 'search', 'mã số', 'mã'])) {
                return $this->searchStudents($db, $messageLower);
            }
            // Nếu là giáo viên và hỏi về sinh viên, ưu tiên hiển thị sinh viên của họ
            if ($_SESSION['role'] === 'teacher') {
                return $this->getMyStudents($db);
            }
            return $this->getStudentStats($db);
        }
        
        // ========== GIẢNG VIÊN ==========
        if ($this->containsKeywords($messageLower, ['giảng viên', 'giáo viên', 'teacher', 'gv'])) {
            if ($this->containsKeywords($messageLower, ['tìm', 'tên', 'search', 'mã số', 'mã'])) {
                return $this->searchTeachers($db, $messageLower);
            }
            return $this->getTeacherStats($db);
        }
        
        // ========== ĐỀ TÀI ==========
        if ($this->containsKeywords($messageLower, ['đề tài', 'topic', 'chủ đề', 'đồ án'])) {
            if ($this->containsKeywords($messageLower, ['chờ duyệt', 'pending', 'chưa duyệt'])) {
                return $this->getPendingTopics($db);
            }
            if ($this->containsKeywords($messageLower, ['tìm', 'search', 'tên'])) {
                return $this->searchTopics($db, $messageLower);
            }
            return $this->getTopicStats($db);
        }
        
        // ========== ĐĂNG KÝ ==========
        if ($this->containsKeywords($messageLower, ['đăng ký', 'registration', 'đăng kí'])) {
            if ($this->containsKeywords($messageLower, ['chờ duyệt', 'pending', 'chưa duyệt'])) {
                return $this->getPendingRegistrations($db);
            }
            return $this->getRegistrationStats($db);
        }
        
        // ========== THỜI GIAN ==========
        if ($this->containsKeywords($messageLower, ['thời gian', 'deadline', 'hạn', 'time setting'])) {
            return $this->getTimeSettings($db);
        }
        
        // ========== BÀI NỘP ==========
        if ($this->containsKeywords($messageLower, ['bài nộp', 'submission', 'nộp bài'])) {
            return $this->getSubmissionStats($db);
        }
        
        // ========== TRỢ GIÚP ==========
        if ($this->containsKeywords($messageLower, ['help', 'trợ giúp', 'hướng dẫn', 'giúp'])) {
            return $this->getHelpMessage();
        }
        
        // ========== SỬ DỤNG GEMINI AI CHO CÁC CÂU HỎI KHÁC ==========
        if (defined('GEMINI_API_KEY') && !empty(GEMINI_API_KEY)) {
            return $this->askGemini($message, $db);
        }
        
        // Default response
        return $this->getDefaultResponse();
    }
    
    /**
     * Gọi Gemini API để trả lời câu hỏi
     */
    private function askGemini($userMessage, $db) {
        // Log để debug
        error_log("askGemini called with message: " . $userMessage);
        
        // Lấy context từ database
        $context = $this->getDatabaseContext($db);
        
        // Lấy thông tin user hiện tại
        $currentUser = $_SESSION['full_name'] ?? 'Người dùng';
        $currentRole = $_SESSION['role'] === 'teacher' ? 'Giảng viên' : 'Quản trị viên';
        
        // Tạo system prompt
        $systemPrompt = "Bạn là trợ lý AI thông minh của hệ thống Quản lý Đồ án Công nghệ Thông tin - Trường Đại học Trà Vinh.

Người đang chat: {$currentUser} ({$currentRole})

🎯 NHIỆM VỤ CHÍNH:
1. Hỗ trợ giảng viên GỢI Ý ĐỀ TÀI đồ án cho sinh viên
2. Tư vấn về công nghệ, framework, ngôn ngữ lập trình phù hợp
3. Đề xuất tính năng, chức năng cho các dự án phần mềm
4. Hỗ trợ quản lý sinh viên, đề tài, đăng ký

📚 KHI ĐƯỢC YÊU CẦU GỢI Ý ĐỀ TÀI:
- Đưa ra 3-5 đề tài cụ thể với tên đề tài rõ ràng
- Mô tả ngắn gọn về đề tài (2-3 câu)
- Liệt kê công nghệ/framework đề xuất
- Nêu các tính năng chính cần có
- Đánh giá độ khó (Dễ/Trung bình/Khó)
- Phù hợp với trình độ sinh viên đại học năm cuối

💡 VÍ DỤ FORMAT GỢI Ý ĐỀ TÀI:
<strong>📋 Đề tài 1: [Tên đề tài]</strong>
• Mô tả: [Mô tả ngắn]
• Công nghệ: [Liệt kê tech stack]
• Tính năng chính: [Các chức năng]
• Độ khó: [Mức độ]

📊 DỮ LIỆU HỆ THỐNG HIỆN TẠI:
{$context}

⚙️ QUY TẮC TRẢ LỜI:
1. Trả lời bằng tiếng Việt, chuyên nghiệp và thân thiện
2. Sử dụng emoji phù hợp để tăng tính trực quan
3. Format bằng HTML đơn giản: <br> xuống dòng, <strong> in đậm, <em> in nghiêng
4. KHÔNG dùng markdown (**, ##, --)
5. Khi gợi ý đề tài, luôn đưa ra nhiều lựa chọn để giảng viên tham khảo
6. Đề xuất các đề tài thực tế, có tính ứng dụng cao
7. Cập nhật xu hướng công nghệ mới nhất (AI, Cloud, Mobile, IoT...)";

        $fullPrompt = $systemPrompt . "\n\n💬 Câu hỏi: " . $userMessage;
        
        try {
            error_log("Calling Gemini API...");
            $response = $this->callGeminiAPI($fullPrompt);
            error_log("Gemini API response received: " . substr($response, 0, 200));
            return $response;
        } catch (Exception $e) {
            error_log("Gemini API error: " . $e->getMessage());
            
            // Nếu là lỗi rate limit (429), trả về gợi ý đề tài mẫu
            if (strpos($e->getMessage(), '429') !== false) {
                return $this->getTopicSuggestionsByKeyword($userMessage);
            }
            
            return "⚠️ Không thể kết nối với AI. Lỗi: " . $e->getMessage() . "<br><br>" . $this->getDefaultResponse();
        }
    }
    
    /**
     * Gợi ý đề tài theo keyword khi AI không khả dụng
     */
    private function getTopicSuggestionsByKeyword($message) {
        $messageLower = mb_strtolower($message, 'UTF-8');
        
        $suggestions = [
            'web' => [
                'title' => 'Đề tài về Web',
                'topics' => [
                    ['name' => 'Website Thương mại điện tử', 'tech' => 'PHP/Laravel, MySQL, Bootstrap', 'level' => 'Trung bình'],
                    ['name' => 'Hệ thống quản lý học tập trực tuyến (LMS)', 'tech' => 'Node.js, React, MongoDB', 'level' => 'Khó'],
                    ['name' => 'Website đặt phòng khách sạn', 'tech' => 'PHP, MySQL, jQuery', 'level' => 'Trung bình'],
                    ['name' => 'Blog cá nhân với CMS', 'tech' => 'Laravel, Vue.js, MySQL', 'level' => 'Dễ'],
                    ['name' => 'Hệ thống quản lý nhân sự', 'tech' => 'ASP.NET Core, SQL Server', 'level' => 'Trung bình'],
                ]
            ],
            'mobile' => [
                'title' => 'Đề tài về Mobile',
                'topics' => [
                    ['name' => 'Ứng dụng quản lý chi tiêu cá nhân', 'tech' => 'Flutter, Firebase', 'level' => 'Dễ'],
                    ['name' => 'App đặt đồ ăn online', 'tech' => 'React Native, Node.js', 'level' => 'Trung bình'],
                    ['name' => 'Ứng dụng học ngoại ngữ', 'tech' => 'Kotlin/Swift, Firebase', 'level' => 'Trung bình'],
                    ['name' => 'App quản lý công việc nhóm', 'tech' => 'Flutter, GraphQL', 'level' => 'Khó'],
                ]
            ],
            'ai' => [
                'title' => 'Đề tài về AI/ML',
                'topics' => [
                    ['name' => 'Chatbot hỗ trợ khách hàng', 'tech' => 'Python, TensorFlow, Flask', 'level' => 'Trung bình'],
                    ['name' => 'Hệ thống nhận diện khuôn mặt', 'tech' => 'Python, OpenCV, Deep Learning', 'level' => 'Khó'],
                    ['name' => 'Dự đoán giá cổ phiếu', 'tech' => 'Python, LSTM, Pandas', 'level' => 'Khó'],
                    ['name' => 'Phân loại hình ảnh sản phẩm', 'tech' => 'Python, CNN, TensorFlow', 'level' => 'Trung bình'],
                ]
            ],
            'iot' => [
                'title' => 'Đề tài về IoT',
                'topics' => [
                    ['name' => 'Hệ thống nhà thông minh', 'tech' => 'Arduino, ESP32, MQTT', 'level' => 'Trung bình'],
                    ['name' => 'Giám sát môi trường nông nghiệp', 'tech' => 'Raspberry Pi, Sensors, Python', 'level' => 'Trung bình'],
                    ['name' => 'Hệ thống điểm danh bằng RFID', 'tech' => 'Arduino, RFID, PHP', 'level' => 'Dễ'],
                ]
            ],
            'default' => [
                'title' => 'Gợi ý đề tài phổ biến',
                'topics' => [
                    ['name' => 'Website quản lý thư viện', 'tech' => 'PHP, MySQL, Bootstrap', 'level' => 'Dễ'],
                    ['name' => 'Ứng dụng quản lý bán hàng POS', 'tech' => 'C#, SQL Server, WinForms', 'level' => 'Trung bình'],
                    ['name' => 'Hệ thống đặt vé xe online', 'tech' => 'Laravel, Vue.js, MySQL', 'level' => 'Trung bình'],
                    ['name' => 'App theo dõi sức khỏe', 'tech' => 'Flutter, Firebase', 'level' => 'Trung bình'],
                    ['name' => 'Website tuyển dụng việc làm', 'tech' => 'Node.js, React, MongoDB', 'level' => 'Trung bình'],
                ]
            ]
        ];
        
        // Xác định category
        $category = 'default';
        if ($this->containsKeywords($messageLower, ['web', 'website', 'trang web'])) {
            $category = 'web';
        } elseif ($this->containsKeywords($messageLower, ['mobile', 'di động', 'app', 'ứng dụng'])) {
            $category = 'mobile';
        } elseif ($this->containsKeywords($messageLower, ['ai', 'machine learning', 'ml', 'trí tuệ nhân tạo', 'học máy'])) {
            $category = 'ai';
        } elseif ($this->containsKeywords($messageLower, ['iot', 'internet of things', 'nhúng', 'arduino', 'raspberry'])) {
            $category = 'iot';
        }
        
        $data = $suggestions[$category];
        
        $result = "💡 <strong>{$data['title']}:</strong><br><br>";
        $result .= "⚠️ <em>AI đang bận, đây là gợi ý mẫu:</em><br><br>";
        
        foreach ($data['topics'] as $index => $topic) {
            $num = $index + 1;
            $result .= "<strong>📋 Đề tài {$num}: {$topic['name']}</strong><br>";
            $result .= "• Công nghệ: {$topic['tech']}<br>";
            $result .= "• Độ khó: {$topic['level']}<br><br>";
        }
        
        $result .= "💡 <em>Thử lại sau vài phút để nhận gợi ý chi tiết từ AI!</em>";
        
        return $result;
    }
    
    /**
     * Gọi Gemini API với hệ thống xoay vòng API key
     */
    private function callGeminiAPI($prompt) {
        $apiKeys = defined('GEMINI_API_KEYS') ? GEMINI_API_KEYS : [GEMINI_API_KEY];
        $lastError = null;
        
        // Thử từng API key cho đến khi thành công
        foreach ($apiKeys as $index => $apiKey) {
            try {
                error_log("Trying API Key " . ($index + 1) . ": " . substr($apiKey, 0, 15) . "...");
                $result = $this->callGeminiWithKey($prompt, $apiKey);
                error_log("API Key " . ($index + 1) . " succeeded!");
                return $result;
            } catch (Exception $e) {
                $lastError = $e;
                error_log("API Key " . ($index + 1) . " failed: " . $e->getMessage());
                
                // Nếu không phải lỗi rate limit (429), throw ngay
                if (strpos($e->getMessage(), '429') === false && strpos($e->getMessage(), 'quota') === false) {
                    throw $e;
                }
                // Nếu là rate limit, thử key tiếp theo
                continue;
            }
        }
        
        // Tất cả key đều fail
        throw $lastError ?? new Exception('All API keys exhausted');
    }
    
    /**
     * Gọi Gemini API với một key cụ thể
     */
    private function callGeminiWithKey($prompt, $apiKey) {
        $url = GEMINI_API_URL . '?key=' . $apiKey;
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 4096,
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ]
            ]
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }
        
        if ($httpCode === 429) {
            throw new Exception("HTTP Error: 429 - Rate limit exceeded");
        }
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP Error: " . $httpCode);
        }
        
        $result = json_decode($response, true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $text = $result['candidates'][0]['content']['parts'][0]['text'];
            return $this->convertMarkdownToHtml($text);
        }
        
        if (isset($result['error'])) {
            throw new Exception($result['error']['message'] ?? 'Unknown API error');
        }
        
        throw new Exception('Invalid API response');
    }
    
    /**
     * Lấy context từ database để cung cấp cho AI
     */
    private function getDatabaseContext($db) {
        $context = "";
        
        // Thống kê tổng quan
        $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
        $students = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'");
        $teachers = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM topics");
        $topics = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM topics WHERE status = 'pending'");
        $pendingTopics = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM registrations");
        $registrations = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM registrations WHERE status = 'pending'");
        $pendingRegistrations = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $context .= "THỐNG KÊ:\n";
        $context .= "- Tổng sinh viên: {$students}\n";
        $context .= "- Tổng giảng viên: {$teachers}\n";
        $context .= "- Tổng đề tài: {$topics}\n";
        $context .= "- Đề tài chờ duyệt: {$pendingTopics}\n";
        $context .= "- Tổng đăng ký: {$registrations}\n";
        $context .= "- Đăng ký chờ duyệt: {$pendingRegistrations}\n\n";
        
        // Danh sách đề tài gần đây
        $stmt = $db->query("
            SELECT t.title, t.status, u.full_name as teacher_name
            FROM topics t
            JOIN users u ON t.teacher_id = u.user_id
            ORDER BY t.created_at DESC
            LIMIT 5
        ");
        $recentTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($recentTopics)) {
            $context .= "ĐỀ TÀI GẦN ĐÂY:\n";
            foreach ($recentTopics as $t) {
                $context .= "- {$t['title']} (GV: {$t['teacher_name']}, Trạng thái: {$t['status']})\n";
            }
            $context .= "\n";
        }
        
        // Danh sách giảng viên
        $stmt = $db->query("
            SELECT u.full_name, COUNT(t.topic_id) as topic_count
            FROM users u
            LEFT JOIN topics t ON u.user_id = t.teacher_id
            WHERE u.role = 'teacher'
            GROUP BY u.user_id
            LIMIT 10
        ");
        $teacherList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($teacherList)) {
            $context .= "DANH SÁCH GIẢNG VIÊN:\n";
            foreach ($teacherList as $t) {
                $context .= "- {$t['full_name']}: {$t['topic_count']} đề tài\n";
            }
        }
        
        return $context;
    }
    
    /**
     * Chuyển đổi Markdown sang HTML đơn giản
     */
    private function convertMarkdownToHtml($text) {
        // Bold: **text** hoặc __text__
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/__(.*?)__/', '<strong>$1</strong>', $text);
        
        // Italic: *text* hoặc _text_
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
        $text = preg_replace('/_(.*?)_/', '<em>$1</em>', $text);
        
        // Line breaks
        $text = nl2br($text);
        
        // Lists: - item hoặc * item
        $text = preg_replace('/^[\-\*]\s+(.*)$/m', '• $1', $text);
        
        return $text;
    }
    
    private function containsKeywords($message, $keywords) {
        foreach ($keywords as $keyword) {
            if (mb_strpos($message, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
    
    private function getOverviewStats($db) {
        $stats = [];
        
        // Count students
        $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
        $stats['students'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Count teachers
        $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'");
        $stats['teachers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Count topics
        $stmt = $db->query("SELECT COUNT(*) as count FROM topics");
        $stats['topics'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Count registrations
        $stmt = $db->query("SELECT COUNT(*) as count FROM registrations");
        $stats['registrations'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Pending topics
        $stmt = $db->query("SELECT COUNT(*) as count FROM topics WHERE status = 'pending'");
        $stats['pending_topics'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Pending registrations
        $stmt = $db->query("SELECT COUNT(*) as count FROM registrations WHERE status = 'pending'");
        $stats['pending_registrations'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return "📊 <strong>Thống kê tổng quan hệ thống:</strong><br><br>" .
               "👨‍🎓 Sinh viên: <strong>{$stats['students']}</strong><br>" .
               "👨‍🏫 Giảng viên: <strong>{$stats['teachers']}</strong><br>" .
               "📋 Đề tài: <strong>{$stats['topics']}</strong><br>" .
               "📝 Đăng ký: <strong>{$stats['registrations']}</strong><br><br>" .
               "⏳ Đề tài chờ duyệt: <strong>{$stats['pending_topics']}</strong><br>" .
               "⏳ Đăng ký chờ duyệt: <strong>{$stats['pending_registrations']}</strong>";
    }
    
    /**
     * Lấy danh sách sinh viên mà giáo viên đang hướng dẫn
     */
    private function getMyStudents($db) {
        if ($_SESSION['role'] !== 'teacher') {
            return $this->getStudentStats($db);
        }
        
        $teacherId = $_SESSION['user_id'];
        
        // Lấy danh sách sinh viên đã đăng ký đề tài của giáo viên này
        $stmt = $db->prepare("
            SELECT u.full_name, u.username as student_code, u.email,
                   t.title as topic_title, r.status, r.registered_at
            FROM registrations r
            JOIN users u ON r.student_id = u.user_id
            JOIN topics t ON r.topic_id = t.topic_id
            WHERE t.teacher_id = ?
            ORDER BY r.status ASC, r.registered_at DESC
        ");
        $stmt->execute([$teacherId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($students)) {
            return "👨‍🏫 <strong>Sinh viên của bạn:</strong><br><br>" .
                   "📭 Hiện tại chưa có sinh viên nào đăng ký đề tài của bạn.<br><br>" .
                   "💡 <em>Hãy tạo thêm đề tài hấp dẫn để thu hút sinh viên!</em>";
        }
        
        $approved = array_filter($students, fn($s) => $s['status'] === 'approved');
        $pending = array_filter($students, fn($s) => $s['status'] === 'pending');
        
        $result = "👨‍🏫 <strong>Sinh viên bạn đang hướng dẫn:</strong><br><br>";
        $result .= "📊 Tổng: <strong>" . count($students) . "</strong> sinh viên<br>";
        $result .= "✅ Đã duyệt: <strong>" . count($approved) . "</strong><br>";
        $result .= "⏳ Chờ duyệt: <strong>" . count($pending) . "</strong><br><br>";
        
        if (!empty($approved)) {
            $result .= "<strong>✅ Sinh viên đã duyệt:</strong><br>";
            foreach ($approved as $s) {
                $result .= "• <strong>{$s['full_name']}</strong> ({$s['student_code']})<br>";
                $result .= "  📋 {$s['topic_title']}<br>";
            }
            $result .= "<br>";
        }
        
        if (!empty($pending)) {
            $result .= "<strong>⏳ Sinh viên chờ duyệt:</strong><br>";
            foreach ($pending as $s) {
                $result .= "• <strong>{$s['full_name']}</strong> ({$s['student_code']})<br>";
                $result .= "  📋 {$s['topic_title']}<br>";
            }
        }
        
        return $result;
    }
    
    private function getStudentStats($db) {
        $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(DISTINCT student_id) as count FROM registrations");
        $registered = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $notRegistered = $total - $registered;
        
        return "👨‍🎓 <strong>Thống kê sinh viên:</strong><br><br>" .
               "📊 Tổng số sinh viên: <strong>{$total}</strong><br>" .
               "✅ Đã đăng ký đề tài: <strong>{$registered}</strong><br>" .
               "❌ Chưa đăng ký: <strong>{$notRegistered}</strong><br><br>" .
               "💡 <em>Gõ \"tìm sinh viên [tên]\" để tìm sinh viên cụ thể</em>";
    }
    
    private function searchStudents($db, $message) {
        // Extract search term
        $searchTerm = $this->extractSearchTerm($message, ['tìm sinh viên', 'tìm sv', 'sinh viên tên', 'mssv']);
        
        if (empty($searchTerm)) {
            return "🔍 Vui lòng nhập tên hoặc MSSV cần tìm.<br>Ví dụ: <em>\"tìm sinh viên Nguyễn Văn A\"</em>";
        }
        
        $stmt = $db->prepare("
            SELECT u.*, 
                   t.title as topic_title,
                   r.status as registration_status
            FROM users u
            LEFT JOIN registrations r ON u.user_id = r.student_id
            LEFT JOIN topics t ON r.topic_id = t.topic_id
            WHERE u.role = 'student' 
            AND (u.full_name LIKE ? OR u.username LIKE ? OR u.email LIKE ?)
            LIMIT 5
        ");
        $searchPattern = "%{$searchTerm}%";
        $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($students)) {
            return "🔍 Không tìm thấy sinh viên với từ khóa: <strong>{$searchTerm}</strong>";
        }
        
        $result = "🔍 <strong>Kết quả tìm kiếm sinh viên:</strong><br><br>";
        foreach ($students as $s) {
            $status = $s['topic_title'] ? "📋 {$s['topic_title']}" : "❌ Chưa đăng ký đề tài";
            $result .= "<div class='result-card'>" .
                       "<h6>👤 {$s['full_name']}</h6>" .
                       "<p>MSSV: {$s['username']}<br>" .
                       "Email: {$s['email']}<br>" .
                       "{$status}</p></div>";
        }
        
        return $result;
    }
    
    private function getTeacherStats($db) {
        $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("
            SELECT u.full_name, COUNT(t.topic_id) as topic_count
            FROM users u
            LEFT JOIN topics t ON u.user_id = t.teacher_id
            WHERE u.role = 'teacher'
            GROUP BY u.user_id
            ORDER BY topic_count DESC
            LIMIT 5
        ");
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $result = "👨‍🏫 <strong>Thống kê giảng viên:</strong><br><br>" .
                  "📊 Tổng số giảng viên: <strong>{$total}</strong><br><br>" .
                  "<strong>Top giảng viên có nhiều đề tài:</strong><br>";
        
        foreach ($teachers as $t) {
            $result .= "• {$t['full_name']}: <strong>{$t['topic_count']}</strong> đề tài<br>";
        }
        
        return $result;
    }
    
    private function searchTeachers($db, $message) {
        $searchTerm = $this->extractSearchTerm($message, ['tìm giảng viên', 'tìm giáo viên', 'tìm gv', 'giảng viên tên', 'giáo viên tên', 'mã giảng viên', 'mã giáo viên', 'mã gv']);
        
        if (empty($searchTerm)) {
            return "🔍 Vui lòng nhập tên hoặc mã giảng viên cần tìm.<br>Ví dụ: <em>\"tìm giảng viên Nguyễn\"</em> hoặc <em>\"tìm mã gv 00255\"</em>";
        }
        
        $stmt = $db->prepare("
            SELECT u.*, COUNT(t.topic_id) as topic_count
            FROM users u
            LEFT JOIN topics t ON u.user_id = t.teacher_id
            WHERE u.role = 'teacher' 
            AND (u.full_name LIKE ? OR u.username LIKE ?)
            GROUP BY u.user_id
            LIMIT 5
        ");
        $searchPattern = "%{$searchTerm}%";
        $stmt->execute([$searchPattern, $searchPattern]);
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($teachers)) {
            return "🔍 Không tìm thấy giảng viên với từ khóa: <strong>{$searchTerm}</strong>";
        }
        
        $result = "🔍 <strong>Kết quả tìm kiếm giảng viên:</strong><br><br>";
        foreach ($teachers as $t) {
            $result .= "<div class='result-card'>" .
                       "<h6>👨‍🏫 {$t['full_name']}</h6>" .
                       "<p>Mã GV: {$t['username']}<br>" .
                       "Email: {$t['email']}<br>" .
                       "Số đề tài: <strong>{$t['topic_count']}</strong></p></div>";
        }
        
        return $result;
    }
    
    private function getTopicStats($db) {
        $stmt = $db->query("SELECT COUNT(*) as count FROM topics");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM topics WHERE status = 'approved'");
        $approved = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM topics WHERE status = 'pending'");
        $pending = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM topics WHERE status = 'rejected'");
        $rejected = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return "📋 <strong>Thống kê đề tài:</strong><br><br>" .
               "📊 Tổng số đề tài: <strong>{$total}</strong><br>" .
               "✅ Đã duyệt: <strong>{$approved}</strong><br>" .
               "⏳ Chờ duyệt: <strong>{$pending}</strong><br>" .
               "❌ Từ chối: <strong>{$rejected}</strong><br><br>" .
               "💡 <em>Gõ \"đề tài chờ duyệt\" để xem danh sách</em>";
    }
    
    private function getPendingTopics($db) {
        $stmt = $db->query("
            SELECT t.*, u.full_name as teacher_name
            FROM topics t
            JOIN users u ON t.teacher_id = u.user_id
            WHERE t.status = 'pending'
            ORDER BY t.created_at DESC
            LIMIT 5
        ");
        $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($topics)) {
            return "✅ Không có đề tài nào đang chờ duyệt!";
        }
        
        $result = "⏳ <strong>Đề tài chờ duyệt:</strong><br><br>";
        foreach ($topics as $t) {
            $result .= "<div class='result-card'>" .
                       "<h6>📋 {$t['title']}</h6>" .
                       "<p>GV: {$t['teacher_name']}<br>" .
                       "<span class='badge bg-warning'>Chờ duyệt</span></p></div>";
        }
        
        $result .= "<br>➡️ <a href='/PHP-CN/public/admin/topics'>Đi đến quản lý đề tài</a>";
        
        return $result;
    }
    
    private function searchTopics($db, $message) {
        $searchTerm = $this->extractSearchTerm($message, ['tìm đề tài', 'đề tài tên', 'topic']);
        
        if (empty($searchTerm)) {
            return "🔍 Vui lòng nhập tên đề tài cần tìm.<br>Ví dụ: <em>\"tìm đề tài website\"</em>";
        }
        
        $stmt = $db->prepare("
            SELECT t.*, u.full_name as teacher_name
            FROM topics t
            JOIN users u ON t.teacher_id = u.user_id
            WHERE t.title LIKE ? OR t.description LIKE ?
            LIMIT 5
        ");
        $searchPattern = "%{$searchTerm}%";
        $stmt->execute([$searchPattern, $searchPattern]);
        $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($topics)) {
            return "🔍 Không tìm thấy đề tài với từ khóa: <strong>{$searchTerm}</strong>";
        }
        
        $result = "🔍 <strong>Kết quả tìm kiếm đề tài:</strong><br><br>";
        foreach ($topics as $t) {
            $statusBadge = $this->getStatusBadge($t['status']);
            $result .= "<div class='result-card'>" .
                       "<h6>📋 {$t['title']}</h6>" .
                       "<p>GV: {$t['teacher_name']}<br>" .
                       "{$statusBadge}</p></div>";
        }
        
        return $result;
    }
    
    private function getRegistrationStats($db) {
        $stmt = $db->query("SELECT COUNT(*) as count FROM registrations");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM registrations WHERE status = 'approved'");
        $approved = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("SELECT COUNT(*) as count FROM registrations WHERE status = 'pending'");
        $pending = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return "📝 <strong>Thống kê đăng ký:</strong><br><br>" .
               "📊 Tổng số đăng ký: <strong>{$total}</strong><br>" .
               "✅ Đã duyệt: <strong>{$approved}</strong><br>" .
               "⏳ Chờ duyệt: <strong>{$pending}</strong><br><br>" .
               "💡 <em>Gõ \"đăng ký chờ duyệt\" để xem danh sách</em>";
    }
    
    private function getPendingRegistrations($db) {
        try {
            // For teacher, only show their registrations
            if ($_SESSION['role'] === 'teacher') {
                $stmt = $db->prepare("
                    SELECT r.*, 
                           s.full_name as student_name,
                           t.title as topic_title
                    FROM registrations r
                    JOIN users s ON r.student_id = s.user_id
                    JOIN topics t ON r.topic_id = t.topic_id
                    WHERE r.status = 'pending' AND t.teacher_id = ?
                    ORDER BY r.registered_at DESC
                    LIMIT 5
                ");
                $stmt->execute([$_SESSION['user_id']]);
            } else {
                $stmt = $db->query("
                    SELECT r.*, 
                           s.full_name as student_name,
                           t.title as topic_title,
                           te.full_name as teacher_name
                    FROM registrations r
                    JOIN users s ON r.student_id = s.user_id
                    JOIN topics t ON r.topic_id = t.topic_id
                    JOIN users te ON t.teacher_id = te.user_id
                    WHERE r.status = 'pending'
                    ORDER BY r.registered_at DESC
                    LIMIT 5
                ");
            }
            
            $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($registrations)) {
                return "✅ Không có đăng ký nào đang chờ duyệt!";
            }
            
            $result = "⏳ <strong>Đăng ký chờ duyệt:</strong><br><br>";
            foreach ($registrations as $r) {
                $result .= "• <strong>{$r['student_name']}</strong><br>";
                $result .= "  📋 {$r['topic_title']}<br><br>";
            }
            
            return $result;
        } catch (Exception $e) {
            return "⚠️ Lỗi khi truy vấn: " . $e->getMessage();
        }
    }
    
    private function getTimeSettings($db) {
        $stmt = $db->query("SELECT * FROM time_settings ORDER BY setting_type");
        $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($settings)) {
            return "⚙️ Chưa có cài đặt thời gian nào trong hệ thống.";
        }
        
        $result = "⏰ <strong>Cài đặt thời gian:</strong><br><br>";
        foreach ($settings as $s) {
            $status = $s['is_active'] ? "<span class='badge bg-success'>Đang mở</span>" : "<span class='badge bg-danger'>Đã đóng</span>";
            $typeName = $this->getSettingTypeName($s['setting_type']);
            $result .= "<div class='result-card'>" .
                       "<h6>📅 {$typeName}</h6>" .
                       "<p>Từ: {$s['start_time']}<br>" .
                       "Đến: {$s['end_time']}<br>" .
                       "{$status}</p></div>";
        }
        
        return $result;
    }
    
    private function getSubmissionStats($db) {
        $stmt = $db->query("SELECT COUNT(*) as count FROM submissions");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $stmt = $db->query("
            SELECT s.*, u.full_name as student_name, t.title as topic_title
            FROM submissions s
            JOIN registrations r ON s.registration_id = r.registration_id
            JOIN users u ON r.student_id = u.user_id
            JOIN topics t ON r.topic_id = t.topic_id
            ORDER BY s.submitted_at DESC
            LIMIT 3
        ");
        $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $result = "📤 <strong>Thống kê bài nộp:</strong><br><br>" .
                  "📊 Tổng số bài nộp: <strong>{$total}</strong><br><br>" .
                  "<strong>Bài nộp gần đây:</strong><br>";
        
        if (!empty($recent)) {
            foreach ($recent as $s) {
                $result .= "<div class='result-card'>" .
                           "<h6>👤 {$s['student_name']}</h6>" .
                           "<p>Đề tài: {$s['topic_title']}<br>" .
                           "Thời gian: {$s['submitted_at']}</p></div>";
            }
        } else {
            $result .= "<em>Chưa có bài nộp nào</em>";
        }
        
        return $result;
    }
    
    private function getHelpMessage() {
        return "📚 <strong>Hướng dẫn sử dụng Trợ lý AI:</strong><br><br>" .
               "🔹 <strong>Thống kê:</strong> \"thống kê tổng quan\", \"dashboard\"<br>" .
               "🔹 <strong>Sinh viên:</strong> \"thống kê sinh viên\", \"tìm sinh viên [tên]\"<br>" .
               "🔹 <strong>Giảng viên:</strong> \"thống kê giảng viên\", \"tìm giảng viên [tên]\"<br>" .
               "🔹 <strong>Đề tài:</strong> \"thống kê đề tài\", \"đề tài chờ duyệt\", \"tìm đề tài [tên]\"<br>" .
               "🔹 <strong>Đăng ký:</strong> \"đăng ký chờ duyệt\"<br>" .
               "🔹 <strong>Thời gian:</strong> \"cài đặt thời gian\"<br>" .
               "🔹 <strong>Bài nộp:</strong> \"thống kê bài nộp\"<br><br>" .
               "💡 <em>Bạn có thể sử dụng các nút nhanh bên dưới!</em>";
    }
    
    private function getDefaultResponse() {
        return "🤔 Tôi chưa hiểu câu hỏi của bạn.<br><br>" .
               "Bạn có thể hỏi về:<br>" .
               "• Thống kê sinh viên, giảng viên, đề tài<br>" .
               "• Tìm kiếm người dùng hoặc đề tài<br>" .
               "• Đăng ký/đề tài chờ duyệt<br>" .
               "• Cài đặt thời gian<br><br>" .
               "💡 Gõ <strong>\"help\"</strong> để xem hướng dẫn chi tiết!";
    }
    
    /**
     * Fallback khi không có Gemini API để gợi ý đề tài
     */
    private function getTopicSuggestionFallback() {
        return "💡 <strong>Gợi ý đề tài đồ án:</strong><br><br>" .
               "⚠️ Tính năng gợi ý đề tài bằng AI chưa được cấu hình.<br><br>" .
               "Để sử dụng tính năng này, vui lòng:<br>" .
               "1. Đăng ký Gemini API key tại <a href='https://makersuite.google.com/app/apikey' target='_blank'>Google AI Studio</a><br>" .
               "2. Thêm API key vào file <code>config/config.php</code><br><br>" .
               "📋 <strong>Một số gợi ý đề tài phổ biến:</strong><br>" .
               "• Website quản lý bán hàng online<br>" .
               "• Ứng dụng di động quản lý công việc<br>" .
               "• Hệ thống quản lý thư viện<br>" .
               "• Website đặt phòng khách sạn<br>" .
               "• Ứng dụng học trực tuyến E-learning";
    }
    
    private function extractSearchTerm($message, $prefixes) {
        // Trước tiên, thử tìm số (MSSV hoặc mã GV) trong message - hỗ trợ cả số có chữ
        if (preg_match('/\b(\d{3,12})\b/', $message, $matches)) {
            return $matches[1];
        }
        
        // Tìm mã có dạng chữ số (ví dụ: GV001, 00255)
        if (preg_match('/\b([a-zA-Z]*\d+[a-zA-Z0-9]*)\b/', $message, $matches)) {
            // Chỉ lấy nếu có ít nhất 2 ký tự số
            if (preg_match('/\d{2,}/', $matches[1])) {
                return $matches[1];
            }
        }
        
        foreach ($prefixes as $prefix) {
            if (mb_strpos($message, $prefix) !== false) {
                $term = trim(mb_substr($message, mb_strpos($message, $prefix) + mb_strlen($prefix)));
                if (!empty($term)) {
                    // Loại bỏ các từ khóa phụ
                    $term = preg_replace('/\b(mã số|mã|số|là|có|tên)\b/iu', '', $term);
                    $term = trim($term);
                    if (!empty($term)) {
                        return $term;
                    }
                }
            }
        }
        
        // Try to extract any meaningful term after common keywords
        $keywords = ['tìm', 'search', 'tên', 'mã số', 'mssv', 'mã'];
        foreach ($keywords as $keyword) {
            if (mb_strpos($message, $keyword) !== false) {
                $parts = explode($keyword, $message);
                if (isset($parts[1])) {
                    $term = trim($parts[1]);
                    // Remove other keywords
                    $term = preg_replace('/\b(sinh viên|giảng viên|giáo viên|đề tài|sv|gv|topic|mã số|mã|số|là|có)\b/iu', '', $term);
                    $term = trim($term);
                    if (!empty($term)) {
                        return $term;
                    }
                }
            }
        }
        
        return '';
    }
    
    private function getStatusBadge($status) {
        switch ($status) {
            case 'approved':
                return "<span class='badge bg-success'>Đã duyệt</span>";
            case 'pending':
                return "<span class='badge bg-warning'>Chờ duyệt</span>";
            case 'rejected':
                return "<span class='badge bg-danger'>Từ chối</span>";
            default:
                return "<span class='badge bg-secondary'>{$status}</span>";
        }
    }
    
    private function getSettingTypeName($type) {
        $names = [
            'topic_creation' => 'Tạo đề tài',
            'topic_registration' => 'Đăng ký đề tài',
            'submission' => 'Nộp bài',
            'progress_report' => 'Báo cáo tiến độ'
        ];
        return $names[$type] ?? $type;
    }
}
