function swal(status, message){
    Swal.fire({
        icon: status,
        title: "Thông báo",
        text: message
    });
}

function swalConfirm(title, text, confrimBtn){
    Swal.fire({
  title: title,
  text: text,
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: confrimBtn
}).then((result) => {
  if (result.isConfirmed) {
    Swal.fire({
      title: "Thông báo!",
      text: "Thành công.",
      icon: "success"
    });
  }
});
}
$(document).ready(function () {
  const currentPath = window.location.pathname;

  $('.nav-link').each(function () {
    const link = $(this).attr('href');
    if (link && currentPath === new URL(link, window.location.origin).pathname) {
      $('.nav-link').removeClass('active'); // Xoá active cũ nếu có
      $(this).addClass('active');
    }
  });
});

new DataTable('table');
