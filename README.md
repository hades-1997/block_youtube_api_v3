Tải file và giải nén



đổi tên thư mục themes nếu bạn không sử dụng giao diện mặc định



Youtube API : lấy từ https://console.developers.google.com/

Youtube Chanel API: lấy từ id của youtube của bạn 



Số lượng hiển thị: số video hiển thị 

Các bước để lấy API key YouTube



Bước 1: Tạo Project mới

Đầu tiên chúng ta sẽ truy cập vào https://console.developers.google.com/ đăng nhập nó bằng tài khoản google rồi chọn CREATE PROJECT để tạo project mới.

create project

Nhập tên project và chọn CREATE.

nhập tên project

Bước 2: Kích hoạt YouTube Data API và tạo API key

Sau khi tạo project mới xong chúng ta ấn vào Library.

library google api

Tại Library các bạn search youtube data api v3 rồi chọn YouTube Data API v3.



Tiếp theo chọn ENABLE để kích hoạt.

kích hoạt api youtube

Chờ 1 lúc cho quá trình kích hoạt diễn ra xong, chúng ta ấn vào CREATE CREDENTIALS để tạo API key.



Bước tiếp theo các bạn hãy tùy chỉnh như trong hình, đối với mục Select an API các bạn chọn YouTube Data API v3. Tùy chỉnh xong bạn chọn Next.



Key API của Youtube sẽ được tạo ra, các bạn ấn vào hình nhỏ nhỏ bên phải để copy key và ấn Done để hoàn tất.



Đến đây, bạn có thể lấy API key để sử dụng. Tuy nhiên, bạn nên áp đặt các restrictions cho API key của để đảm bảo sự an toàn và hiệu quả khi sử dụng. Nếu không áp đặt restrictions, API key của bạn có thể bị sử dụng bởi bất kỳ ai trên mạng, dẫn đến các vấn đề bảo mật hoặc tài nguyên.

Các restrictions phổ biến bao gồm giới hạn cho phép yêu cầu từ các địa chỉ IP cụ thể, hoặc chỉ cho phép sử dụng API key của bạn cho các mục đích cụ thể(Websites, Apps). Vì vậy, hãy áp đặt các restrictions để đảm bảo rằng API key của bạn được sử dụng một cách an toàn và hiệu quả.

Cấu hình Restrictions API key YouTube

Để cấu hình Restrictions và quản lý API key bạn truy cập vào mục Credentials. Tại đây sẽ hiển thị danh sách các API Key mà bạn đã tạo, bạn có thể xóa hoặc sửa tại cột Actions và SHOW KEY để hiện key.



Tiếp theo, bạn chọn một API key cần Restrictions rồi cấu hình theo nhu cầu.



Set an application restriction: Giới hạn việc sử dụng API Key đối với các trang web, địa chỉ IP, Apps cụ thể. 

API restrictions: Chỉ định các API mà bạn có thể gọi thông qua Key API này(Giúp bạn phân tách các Key, mỗi Key gọi 1 API hoặc 1 Key có thể gọi nhiều API khác nhau).

Don’t restrict key: Sử dụng tùy chọn này nếu bạn muốn Key của mình đa năng, có thể gọi tất cả API mà bạn đã kích hoạt.

Restrict key: Chọn một hoặc nhiều API mà Key có thể gọi theo nhu cầu.

Sau khi cấu hình xong chọn SAVE để lưu lại.

