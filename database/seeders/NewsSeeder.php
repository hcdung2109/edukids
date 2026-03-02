<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class NewsSeeder extends Seeder
{
    /**
     * Xóa toàn bộ tin tức cũ và insert lại dữ liệu mẫu.
     * Nội dung mô phỏng fanpage: https://www.facebook.com/ToHopCongNgheGiaoDucEDUKIDS
     * Ảnh thật: tải từ Facebook sau đó upload qua trang quản trị (Tin tức & Sự kiện > Sửa bài > Ảnh đại diện / Thư viện ảnh).
     */
    public function run(): void
    {
        News::query()->delete();

        $placeholderPath = 'news/covers/default.svg';
        $source = public_path('images/news-placeholder.svg');
        if (file_exists($source)) {
            Storage::disk('public')->put($placeholderPath, file_get_contents($source));
        }

        $items = [
            [
                'title' => 'Khai giảng khóa Robotics cơ bản – Tháng 3/2025',
                'excerpt' => 'EduKids chính thức khai giảng khóa Robotics cơ bản dành cho trẻ 7–10 tuổi. Đăng ký ngay để nhận ưu đãi.',
                'body' => "Tổ Hợp Công Nghệ Giáo Dục EduKids thông báo khai giảng khóa Robotics cơ bản tháng 3/2025.\n\nKhóa học phù hợp trẻ 7–10 tuổi, giúp các em làm quen lắp ráp, lập trình robot và phát triển tư duy logic.\n\nƯu đãi đăng ký sớm: giảm 10% học phí khi đăng ký trước ngày 15/3. Liên hệ fanpage hoặc hotline để được tư vấn.",
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Cuộc thi Lập trình sáng tạo cho bé 2025',
                'excerpt' => 'Cuộc thi Lập trình sáng tạo dành cho học sinh tiểu học. Cơ hội thể hiện tài năng và nhận giải thưởng giá trị.',
                'body' => "EduKids phối hợp tổ chức Cuộc thi Lập trình sáng tạo cho bé 2025.\n\nĐối tượng: học sinh tiểu học (lớp 2–5).\n\nCác em sẽ thiết kế sản phẩm phần mềm hoặc game đơn giản bằng công cụ kéo thả. Ban giám khảo đánh giá theo ý tưởng, giao diện và logic chương trình.\n\nHạn đăng ký: 30/3/2025. Theo dõi fanpage để cập nhật thể lệ và giải thưởng.",
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => '5 lý do nên cho trẻ học STEM từ sớm',
                'excerpt' => 'STEM giúp trẻ phát triển tư duy logic, sáng tạo và kỹ năng giải quyết vấn đề. Cùng EduKids tìm hiểu 5 lý do nên bắt đầu sớm.',
                'body' => "1. Phát triển tư duy logic và phân tích.\n2. Rèn kỹ năng làm việc nhóm và giao tiếp.\n3. Khơi dậy sự tò mò và ham học hỏi.\n4. Chuẩn bị hành trang cho nghề nghiệp tương lai.\n5. Học qua thực hành – trẻ vừa chơi vừa học, không áp lực.\n\nEduKids thiết kế chương trình STEM phù hợp từng độ tuổi. Liên hệ để được tư vấn lộ trình học cho con.",
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Lớp học trải nghiệm miễn phí – Robotics & Lập trình',
                'excerpt' => 'Đăng ký tham gia lớp học trải nghiệm miễn phí Robotics và Lập trình cho trẻ. Số lượng có hạn.',
                'body' => "EduKids mở lớp học trải nghiệm miễn phí Robotics và Lập trình cho trẻ 6–12 tuổi.\n\nBuổi học giúp phụ huynh và các em làm quen môi trường, giáo viên và phương pháp học tại trung tâm.\n\nThời gian: Chủ nhật hàng tuần. Đăng ký qua inbox fanpage hoặc form trên website. Số lượng mỗi buổi có giới hạn.",
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Học sinh EduKids đạt giải Nhì cuộc thi Robot toàn quốc',
                'excerpt' => 'Chúc mừng đội tuyển EduKids đạt giải Nhì tại cuộc thi Robot dành cho học sinh tiểu học toàn quốc.',
                'body' => "Đội tuyển Robotics EduKids đã xuất sắc giành giải Nhì tại cuộc thi Robot toàn quốc dành cho học sinh tiểu học.\n\nThành tích này là minh chứng cho chất lượng đào tạo và sự nỗ lực của các em. EduKids cảm ơn quý phụ huynh đã tin tưởng và đồng hành.",
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Tips cho phụ huynh: Cùng con học lập trình tại nhà',
                'excerpt' => 'Gợi ý cách phụ huynh có thể đồng hành và hỗ trợ con học lập trình tại nhà một cách vui vẻ, hiệu quả.',
                'body' => "1. Chọn công cụ phù hợp lứa tuổi (Scratch, Code.org...).\n2. Đặt mục tiêu nhỏ, khen ngợi từng bước.\n3. Khuyến khích con thử và sai, không sợ lỗi.\n4. Dành 20–30 phút mỗi ngày thay vì học dồn.\n5. Kết nối với giáo viên EduKids để được gợi ý bài tập và dự án phù hợp.",
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Khai giảng khóa Kỹ năng thế kỷ 21 – Thuyết trình & Làm việc nhóm',
                'excerpt' => 'Khóa học giúp trẻ rèn kỹ năng thuyết trình, làm việc nhóm và tư duy phản biện. Khai giảng tháng 3.',
                'body' => "EduKids mở khóa Kỹ năng thế kỷ 21 với nội dung: thuyết trình trước đám đông, làm việc nhóm, tư duy phản biện và quản lý thời gian.\n\nKhóa phù hợp trẻ 8–14 tuổi. Học qua dự án thực tế và hoạt động nhóm. Đăng ký sớm nhận ưu đãi.",
                'published_at' => now()->subDays(16),
            ],
            [
                'title' => 'Sự kiện Ngày hội STEM – Trải nghiệm miễn phí cho cả gia đình',
                'excerpt' => 'Ngày hội STEM với nhiều gian hàng trải nghiệm Robotics, lập trình, khoa học vui. Vào cửa miễn phí.',
                'body' => "Ngày hội STEM EduKids – cơ hội cho cả gia đình trải nghiệm Robotics, lập trình và các thí nghiệm khoa học vui.\n\nThời gian: 9h–17h, Chủ nhật 23/3/2025.\n\nĐịa điểm: Trung tâm EduKids. Vào cửa miễn phí. Đăng ký tham gia qua fanpage để nhận quà tặng.",
                'published_at' => now()->subDays(18),
            ],
            [
                'title' => 'Tuyển dụng Giáo viên dạy Robotics & Lập trình cho trẻ',
                'excerpt' => 'EduKids tuyển giáo viên đam mê giáo dục công nghệ, có kinh nghiệm hoặc được đào tạo bài bản.',
                'body' => "EduKids cần tuyển giáo viên dạy Robotics và Lập trình cho trẻ em.\n\nYêu cầu: yêu trẻ, có tinh thần học hỏi, ưu tiên có kinh nghiệm STEM/lập trình. EduKids sẽ đào tạo phương pháp và giáo trình.\n\nMôi trường làm việc trẻ trung, lương cạnh tranh. Gửi CV qua email hoặc inbox fanpage.",
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Lịch học các khóa tháng 3 – Cập nhật mới',
                'excerpt' => 'Cập nhật lịch học các khóa Robotics, STEM, Lập trình và Kỹ năng tháng 3/2025. Phụ huynh xem và đăng ký.',
                'body' => "Lịch học tháng 3/2025:\n\n- Robotics cơ bản: T3, T5 18h–19h30.\n- STEM khám phá: T4, T6 18h–19h30.\n- Lập trình Scratch: T7 8h30–10h.\n- Kỹ năng thế kỷ 21: Chủ nhật 14h–16h.\n\nLiên hệ để xếp lịch học thử và tư vấn lộ trình phù hợp với con.",
                'published_at' => now()->subDays(22),
            ],
            [
                'title' => 'Câu chuyện từ phụ huynh: Con tiến bộ rõ rệt sau 3 tháng học Robotics',
                'excerpt' => 'Chia sẻ từ phụ huynh có con theo học Robotics tại EduKids – từ nhút nhát đến tự tin thuyết trình ý tưởng.',
                'body' => "Chị Hương (quận 7) chia sẻ: 'Bé Minh ban đầu rất nhút nhát. Sau 3 tháng học Robotics tại EduKids, con tự tin hơn, biết trình bày ý tưởng và làm việc nhóm. Gia đình rất hài lòng.'\n\nEduKids cảm ơn sự tin tưởng của quý phụ huynh. Chúng tôi cam kết đồng hành cùng con trên hành trình phát triển.",
                'published_at' => now()->subDays(24),
            ],
            [
                'title' => 'Hướng dẫn đăng ký khóa học online qua website',
                'excerpt' => 'Phụ huynh có thể đăng ký khóa học và xem lịch qua website. Bài viết hướng dẫn từng bước.',
                'body' => "Bước 1: Truy cập website EduKids.\nBước 2: Chọn mục Khóa học / Liên hệ.\nBước 3: Điền form (tên phụ huynh, SĐT, email, khóa quan tâm).\nBước 4: Nhân viên sẽ gọi lại tư vấn trong 24h.\n\nHoặc inbox trực tiếp fanpage Facebook Tổ Hợp Công Nghệ Giáo Dục EduKids để được hỗ trợ nhanh.",
                'published_at' => now()->subDays(26),
            ],
            [
                'title' => 'Ra mắt chương trình EduKids Junior – Dành cho trẻ 5–6 tuổi',
                'excerpt' => 'Chương trình mới EduKids Junior giúp trẻ 5–6 tuổi làm quen tư duy lập trình và khoa học qua trò chơi.',
                'body' => "EduKids Junior là chương trình đặc biệt cho trẻ 5–6 tuổi.\n\nCác em làm quen với tư duy lập trình (thứ tự, điều kiện) và khoa học qua trò chơi, không cần dùng máy tính nhiều. Giáo viên được đào tạo chuyên biệt cho lứa tuổi mầm non.\n\nKhai giảng tháng 4. Đăng ký sớm nhận ưu đãi.",
                'published_at' => now()->subDays(28),
            ],
            [
                'title' => 'Hợp tác với trường Tiểu học ABC – Đưa STEM vào chương trình ngoại khóa',
                'excerpt' => 'EduKids ký kết hợp tác với trường Tiểu học ABC đưa STEM và Robotics vào chương trình ngoại khóa.',
                'body' => "EduKids vừa ký kết hợp tác với trường Tiểu học ABC. Các lớp Robotics và STEM sẽ được đưa vào chương trình ngoại khóa cho học sinh toàn trường.\n\nĐây là bước tiến trong việc phổ cập giáo dục công nghệ cho trẻ em. Chúng tôi hy vọng sẽ mở rộng mô hình này đến nhiều trường hơn.",
                'published_at' => now()->subDays(30),
            ],
            [
                'title' => 'Thông báo nghỉ Tết Nguyên đán 2025',
                'excerpt' => 'Lịch nghỉ Tết và lịch học lại sau Tết. Phụ huynh lưu ý để sắp xếp đưa đón con.',
                'body' => "EduKids thông báo nghỉ Tết Nguyên đán từ 27/1 đến hết 02/2 (âm lịch).\n\nHọc lại từ thứ Hai 03/2. Trung tâm gửi lời chúc quý phụ huynh và các em một năm mới an khang, học tập tiến bộ.",
                'published_at' => now()->subDays(35),
            ],
            [
                'title' => 'Workshop: Cùng con lắp robot tại nhà',
                'excerpt' => 'Workshop trực tuyến hướng dẫn phụ huynh và con cùng lắp ráp robot đơn giản với bộ dụng cụ có sẵn.',
                'body' => "Workshop 'Cùng con lắp robot tại nhà' diễn ra vào 15h Chủ nhật 16/3, qua Zoom.\n\nPhụ huynh đăng ký sẽ nhận link tham gia và danh sách dụng cụ (có thể mua tại cửa hàng đồ chơi hoặc qua EduKids).\n\nĐăng ký miễn phí qua fanpage. Số lượng có hạn.",
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'EduKids tham gia Ngày hội Công nghệ giáo dục 2025',
                'excerpt' => 'EduKids sẽ có gian hàng tại Ngày hội Công nghệ giáo dục. Mời quý phụ huynh và các em ghé thăm.',
                'body' => "Ngày hội Công nghệ giáo dục 2025 quy tụ nhiều đơn vị giáo dục STEM. EduKids tham gia với gian hàng trải nghiệm Robotics và Lập trình.\n\nĐịa điểm: Trung tâm Hội chợ XYZ. Thời gian: 8–10/3/2025. Vào cửa miễn phí. Hẹn gặp mọi người tại gian EduKids.",
                'published_at' => now()->subDays(11),
            ],
            [
                'title' => 'Chính sách học thử và hoàn học phí',
                'excerpt' => 'EduKids công bố chính sách học thử 1 buổi miễn phí và cam kết hoàn học phí trong 2 tuần đầu nếu không hài lòng.',
                'body' => "Học thử: 1 buổi miễn phí, không bắt buộc đăng ký.\n\nSau khi đăng ký chính thức: trong 2 tuần đầu nếu phụ huynh không hài lòng về chất lượng, EduKids sẽ hoàn 100% học phí đã đóng.\n\nMục tiêu: phụ huynh yên tâm khi gửi con, EduKids cam kết chất lượng đào tạo.",
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Giới thiệu giáo viên mới: Thầy Tuấn – Chuyên gia Robotics',
                'excerpt' => 'Thầy Tuấn gia nhập đội ngũ EduKids với kinh nghiệm 5 năm dạy Robotics và tham gia nhiều cuộc thi quốc tế.',
                'body' => "Thầy Nguyễn Văn Tuấn tốt nghiệp ĐH Bách khoa, từng tham gia và hướng dẫn đội thi Robotics quốc tế.\n\nThầy sẽ phụ trách các lớp Robotics nâng cao và luyện thi. Phụ huynh quan tâm có thể đăng ký học thử với thầy Tuấn qua trung tâm.",
                'published_at' => now()->subDays(19),
            ],
            [
                'title' => 'Bảng học phí các khóa học – Cập nhật 2025',
                'excerpt' => 'Bảng học phí các khóa Robotics, STEM, Lập trình và Kỹ năng. Áp dụng từ 1/3/2025.',
                'body' => "Robotics cơ bản: 2.500.000đ/tháng (8 buổi).\nSTEM khám phá: 2.200.000đ/tháng (8 buổi).\nLập trình Scratch: 2.400.000đ/tháng (8 buổi).\nKỹ năng thế kỷ 21: 2.000.000đ/tháng (4 buổi).\n\nĐăng ký 3 tháng giảm 5%, 6 tháng giảm 10%. Chi tiết inbox fanpage.",
                'published_at' => now()->subDays(21),
            ],
            [
                'title' => 'Kết quả kiểm tra đánh giá năng lực đầu khóa – Tháng 2',
                'excerpt' => 'Thông báo kết quả kiểm tra đánh giá năng lực đầu khóa tháng 2. Phụ huynh xem và trao đổi với giáo viên.',
                'body' => "EduKids đã hoàn thành đánh giá năng lực đầu khóa cho các em đăng ký tháng 2.\n\nKết quả và lời khuyên lộ trình học đã được gửi qua email và Zalo. Phụ huynh cần tư vấn thêm vui lòng liên hệ giáo viên phụ trách hoặc hotline.",
                'published_at' => now()->subDays(23),
            ],
            [
                'title' => 'Câu lạc bộ Lập trình EduKids – Sinh hoạt hàng tháng',
                'excerpt' => 'Câu lạc bộ Lập trình dành cho học viên đã hoàn thành khóa cơ bản. Sinh hoạt 1 lần/tháng, có dự án và thuyết trình.',
                'body' => "CLB Lập trình EduKids dành cho học viên từ 10 tuổi trở lên đã học xong khóa Scratch cơ bản.\n\nMỗi tháng 1 buổi: các em làm dự án nhóm, thuyết trình và nhận góp ý. Miễn phí tham gia cho học viên đang theo học tại trung tâm. Đăng ký qua giáo viên.",
                'published_at' => now()->subDays(25),
            ],
            [
                'title' => 'Phòng học mới – Cơ sở 2 EduKids',
                'excerpt' => 'EduKids mở cơ sở 2 với phòng học rộng rãi, trang thiết bị Robotics và máy tính hiện đại.',
                'body' => "Cơ sở 2 EduKids chính thức đi vào hoạt động với 4 phòng học, mỗi phòng 12–15 học viên.\n\nTrang thiết bị: bộ Lego Education, máy tính cấu hình tốt, màn hình trình chiếu. Địa chỉ: [địa chỉ]. Đăng ký học tại cơ sở 2 qua fanpage hoặc hotline.",
                'published_at' => now()->subDays(27),
            ],
            [
                'title' => 'Video: Học sinh EduKids giới thiệu sản phẩm Robot dò đường',
                'excerpt' => 'Clip ngắn các em học sinh giới thiệu sản phẩm Robot dò đường tự làm trong khóa Robotics nâng cao.',
                'body' => "Video mới trên fanpage: các em học sinh lớp Robotics nâng cao giới thiệu sản phẩm Robot dò đường do chính các em lắp ráp và lập trình.\n\nQuý phụ huynh và mọi người xem tại fanpage Tổ Hợp Công Nghệ Giáo Dục EduKids. Đừng quên like và share để ủng hộ các em.",
                'published_at' => now()->subDays(29),
            ],
            [
                'title' => 'Tổng kết năm 2024 – Cảm ơn quý phụ huynh và học viên',
                'excerpt' => 'EduKids gửi lời cảm ơn đến quý phụ huynh và các em học viên đã đồng hành trong năm 2024.',
                'body' => "Năm 2024 EduKids đã đón hàng trăm học viên, tổ chức nhiều sự kiện và cuộc thi. Chúng tôi xin gửi lời cảm ơn chân thành đến quý phụ huynh và các em.\n\nNăm 2025 EduKids tiếp tục nâng cao chất lượng và mở rộng chương trình. Kính chúc mọi người năm mới vui khỏe, học tập tiến bộ.",
                'published_at' => now()->subDays(40),
            ],
            [
                'title' => 'Thông báo lịch thi cấp chứng nhận hoàn thành khóa Robotics',
                'excerpt' => 'Lịch thi cấp chứng nhận hoàn thành khóa Robotics tháng 3. Học viên đủ điều kiện vui lòng đăng ký dự thi.',
                'body' => "Kỳ thi cấp chứng nhận hoàn thành khóa Robotics tháng 3/2025 dự kiến tổ chức vào ngày 28/3.\n\nĐối tượng: học viên đã hoàn thành ít nhất 80% chương trình và đạt bài kiểm tra cuối khóa. Đăng ký dự thi qua giáo viên trước 20/3.",
                'published_at' => now()->subDays(9),
            ],
            [
                'title' => 'Góc chia sẻ: Bé Lan say mê lập trình sau khóa Scratch',
                'excerpt' => 'Bé Lan (9 tuổi) từ không thích máy tính đến say mê làm game sau 4 tháng học Scratch tại EduKids.',
                'body' => "Chị Hà (phụ huynh bé Lan) kể: 'Trước đây con chỉ xem YouTube. Từ khi học Scratch tại EduKids, con tự làm game đơn giản và khoe với cả nhà. Gia đình rất vui.'\n\nEduKids tin rằng mỗi trẻ đều có thể yêu thích công nghệ nếu được hướng dẫn đúng cách. Hãy để con thử một buổi học.",
                'published_at' => now()->subDays(13),
            ],
            [
                'title' => 'Hỗ trợ học phí cho học sinh có hoàn cảnh khó khăn',
                'excerpt' => 'EduKids dành 5 suất hỗ trợ 50% học phí cho học sinh có hoàn cảnh khó khăn, đam mê công nghệ.',
                'body' => "Chương trình 'Cùng em đến với công nghệ' – EduKids hỗ trợ 50% học phí cho 5 em có hoàn cảnh khó khăn nhưng đam mê Robotics/Lập trình.\n\nĐiều kiện: đang học tiểu học, có đơn xin hỗ trợ và xác nhận từ địa phương. Hạn nộp: 25/3/2025. Chi tiết inbox fanpage.",
                'published_at' => now()->subDays(17),
            ],
            [
                'title' => 'Ra mắt kênh YouTube EduKids – Bài giảng và thí nghiệm miễn phí',
                'excerpt' => 'Kênh YouTube EduKids chính thức ra mắt với các clip bài giảng và thí nghiệm khoa học vui cho trẻ.',
                'body' => "Kênh YouTube 'EduKids – Tổ Hợp Công Nghệ Giáo Dục' đã lên sóng.\n\nNội dung: bài giảng Scratch ngắn gọn, thí nghiệm khoa học vui, giới thiệu sản phẩm của học sinh. Phụ huynh và các em có thể xem miễn phí. Nhớ subscribe để nhận thông báo video mới.",
                'published_at' => now()->subDays(31),
            ],
            [
                'title' => 'Hỏi đáp: Trẻ mấy tuổi có thể bắt đầu học lập trình?',
                'excerpt' => 'Câu hỏi nhiều phụ huynh quan tâm. EduKids gợi ý độ tuổi và hình thức học phù hợp cho từng lứa tuổi.',
                'body' => "5–6 tuổi: làm quen tư duy lập trình qua trò chơi, không cần máy (EduKids Junior).\n7–9 tuổi: Scratch, lập trình kéo thả.\n10–12 tuổi: Scratch nâng cao, có thể chuyển sang Python cơ bản.\n\nQuan trọng là phù hợp sở thích và tốc độ của con. EduKids tư vấn lộ trình cá nhân hóa khi đăng ký.",
                'published_at' => now()->subDays(33),
            ],
            [
                'title' => 'Đối tác mới: Cung cấp giáo trình STEM cho trường học',
                'excerpt' => 'EduKids ký hợp tác cung cấp giáo trình và đào tạo giáo viên STEM cho các trường tiểu học.',
                'body' => "EduKids mở rộng hợp tác với các trường tiểu học: cung cấp giáo trình STEM, tài liệu và đào tạo giáo viên.\n\nTrường quan tâm vui lòng liên hệ qua email hoặc fanpage để nhận bản giới thiệu và báo giá. Chúng tôi cam kết hỗ trợ triển khai bài bản.",
                'published_at' => now()->subDays(37),
            ],
            [
                'title' => 'Chương trình Ưu đãi tháng 3 – Tặng bộ dụng cụ Robotics khi đăng ký 3 tháng',
                'excerpt' => 'Đăng ký khóa Robotics 3 tháng trong tháng 3/2025, phụ huynh được tặng bộ dụng cụ lắp ráp cơ bản cho con luyện tại nhà.',
                'body' => "Trong tháng 3/2025, EduKids triển khai chương trình ưu đãi: đăng ký khóa Robotics 3 tháng trở lên sẽ được tặng 1 bộ dụng cụ lắp ráp cơ bản (robot nhỏ) để các em có thể ôn tập và sáng tạo tại nhà.\n\nSố lượng quà tặng có hạn. Liên hệ fanpage hoặc hotline để đăng ký và nhận ưu đãi.",
                'published_at' => now()->subDays(4),
            ],
        ];

        $defaultImage = file_exists($source) ? $placeholderPath : null;

        foreach ($items as $item) {
            News::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($item['title'])],
                array_merge($item, [
                    'is_published' => true,
                    'image' => $defaultImage,
                ])
            );
        }
    }
}
