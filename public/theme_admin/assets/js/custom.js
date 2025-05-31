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