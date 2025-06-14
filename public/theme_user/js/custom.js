function swal(status, message){
    Swal.fire({
        icon: status,
        title: "Thông báo",
        text: message
    });
}

    function cleanExpiredBookings() {
        const raw = Cookies.get('bookingCart');
        if (!raw) return;

        try {
            const data = JSON.parse(raw);
            const now = new Date();
            const today = new Date(now.toDateString());
            const currentHour = now.getHours();

            const valid = (data.selections || []).filter(sel => {
            const bookingDate = new Date(sel.date + 'T00:00:00');

            if (bookingDate > today) return true;                 // tương lai OK
            if (bookingDate.getTime() === today.getTime()) {
                return (sel.endHour || 24) > currentHour;           // hôm nay + còn giờ
            }
            return false;                                         // quá khứ ⇒ bỏ
            });

            if (valid.length !== (data.selections || []).length) {
            Cookies.set('bookingCart', JSON.stringify({ selections: valid }), { expires: 7 });
            }
        } catch (e) {
            console.error('Lỗi check giỏ hàng:', e);
        }
    }
function updateCartBadge() {
    var $badge = $('#cart-count');
    if ($badge.length === 0) return; // Không có phần tử badge

    try {
        var raw = Cookies.get('bookingCart');
        var data = raw ? JSON.parse(raw) : {};
        var count = Array.isArray(data.selections) ? data.selections.length : 0;
        $badge.text(count);
    } catch (e) {
        console.error('Lỗi đọc bookingCart:', e);
        $badge.text(0);
    }
}
    $(document).ready(function () {
        cleanExpiredBookings();
        updateCartBadge(); 
    });