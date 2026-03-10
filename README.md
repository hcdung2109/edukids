Website bằng laravel , giới thiệu  công ty Nền tảng giáo dục đa công nghệ 
Đào tạo Robotics - STEM - Lập trình - Kỹ năng - Bồi dưỡng kiến thức cho trẻ em

- Sử dụng kết nối database MySQL (cấu hình trong `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)

Trang quản trị (AdminLTE):
- Đăng nhập tại `/login`. Tài khoản admin mặc định được tạo bởi `php artisan db:seed` (xem `DatabaseSeeder`).
- Chỉ user có role **admin** mới truy cập được `/admin`. User **teacher** đăng nhập vào dashboard thường.
- Quản lý danh sách tài khoản tại `/admin/users`: thêm/sửa/xóa, 2 loại role: **admin**, **teacher**
- Quản lý liên hệ từ trang chủ
- Quản lý tin tức được sử dụng hiển thị trong trang chủ. **Xóa hết tin cũ + thêm lại dữ liệu mẫu:** `php artisan db:seed --class=NewsSeeder --force`. Ảnh từ [fanpage Facebook EduKids](https://www.facebook.com/ToHopCongNgheGiaoDucEDUKIDS): tải ảnh về máy rồi vào Admin → Tin tức & Sự kiện → Sửa từng bài → upload Ảnh đại diện / Thư viện ảnh.
- Quản lý site : Các thông tin liên hệ, địa chỉ, email, facebook, số điện thoại,... đang hiển trị ở trang chủ 
- Quản lý danh sách khóa học
- Quản lý danh sách trung tâm
- Quản lý danh sách lớp học theo trung tâm
- Quản lý danh sách học viên theo lớp học của trung tâm
- Quản lý danh sách buổi học theo lớp của trung tâm, có thể xem theo dạng lịch tháng
- Chức năng điểm danh học viên của buổi học, xuất file excel điểm danh
- Quản lý danh sách file tài liệu tải lên (word , pdf , powerpoint ...) , gán quyền xem tài liệu theo từng lớp học, tài liệu chỉ có quyền xem online không thẻ tải
- Màn hình dashboard thể hiện danh sách các trung tâm 
- Chức năng quản lý công cụ học :  tên công cụ , số lượng, ghi chú, trong đó công cụ được gán trung tâm nào, lớp nào, quản lý ai đang quản lý
- Thêm chức năng phân quyền
- Cho phép hiển thị cả thời gian ca học nếu có ở trên lịch ví dụ hiển thị label 8h-12h ở trên ngày bất kỳ của lịch
- Chức năng thông kê trong màn hình dashboad : 
Hiển thị bảng danh sách giáo viên - thống kê số dậy học của giáo viên trong tháng

- khi giáo viên điểm danh học viên của buổi học, => tự động lưu điểm danh giáo viên , tại màn hình dashboard của admin, hiển thị bảng thống kê số buổi giảng dậy trong mỗi tháng của giáo viên đó, cần thêm bộ lọc (gồm filter theo khoảng thời gian, option giáo viên , nút tìm kiếm), 


- Khi thêm / sửa lớp học bổ sung thêm trường thông tin: số giờ  mỗi buổi học, trong màn hình "Lịch buổi học"  khi đánh dấu buổi học hiển thị số giờ mặc định mỗi buổi học dạng text

- Chức năng quản lý giáo viên:
+ Điểm danh giáo viên theo ngày , có ô nhập tổng số giờ của ngày đó
+ khi giáo viên điểm danh học viên buổi của buổi học , tự động động điểm danh giáo viên của ngày đó và số giờ dậy bằng số giờ của buổi học
+ Tại màn hìn dashboard, hiển thị bảng thống kê tổng số giờ đã dậy trong tháng của mỗi giáo viên, Có thể lọc theo khoảng thời gian từ ngày - đến ngày 

- box lớp "Lớp đang học" nếu account là teacher thì chỉ hiển thị danh sách lớp đang học của giáo viên đó , chỉ có account admin mới show all


laravel note
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear