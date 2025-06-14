@extends('layouts.user.app')

@section('title', 'Trang chủ')

@section('styles')
<style>
    /* CSS tùy chỉnh sẽ nằm ở đây */
    /* Đảm bảo chiều cao ảnh cố định */
    .product-card .card-img-top {
        width: 100%;
        height: 200px; /* Chiều cao cố định cho ảnh */
        object-fit: cover; /* Đảm bảo ảnh không bị méo và cắt vừa khung */
        transition: transform 0.3s ease-in-out;
    }

    /* Đảm bảo chiều cao cho phần tiêu đề */
    .product-card .card-title {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: #343a40;
        /* Thêm các thuộc tính sau để kiểm soát chiều cao tiêu đề */
        height: 3em; /* Đặt chiều cao cố định cho 2 dòng văn bản (giả sử font-size 1.25rem, line-height khoảng 1.2) */
        overflow: hidden; /* Cắt bỏ phần text vượt quá */
        text-overflow: ellipsis; /* Thêm dấu "..." nếu text bị cắt */
        display: -webkit-box; /* Dùng cho Webkit (Chrome, Safari) */
        -webkit-line-clamp: 2; /* Giới hạn 2 dòng */
        -webkit-box-orient: vertical; /* Hướng hiển thị */
    }

    /* Các thuộc tính CSS khác từ trước */
    .product-card {
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        overflow: hidden;
        position: relative;
        transition: box-shadow 0.3s ease-in-out;
        background-color: #fff;
        /* Thêm flexbox để card tự co giãn chiều cao */
        display: flex;
        flex-direction: column; /* Đảm bảo các phần tử xếp dọc */
        height: 100%; /* Quan trọng để card con giãn hết chiều cao của cột cha */
    }

    .product-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .product-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .product-card .card-body {
        padding: 1rem;
        text-align: center;
        /* Quan trọng: Cho phép card-body giãn nở để đẩy overlay xuống cuối */
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center; /* Căn giữa tiêu đề theo chiều dọc nếu có không gian thừa */
    }

    .product-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .product-card:hover .product-card-overlay {
        opacity: 1;
    }

    .product-card-overlay .btn {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    .product-card:hover .product-card-overlay .btn {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection

@section('mainsection')

    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row">
                @foreach ($sports as $sport)
                <div class="col-md-3 mb-4 d-flex">
                    <div class="product-card">
                        <img src="{{ asset('/storage/'. $sport->icon) }}" class="card-img-top" alt="Product 1">
                        <div class="card-body">
                            <h5 class="card-title">Đặt Sân {{ $sport->name }}</h5>
                        </div>
                        <div class="product-card-overlay">
                            <a href="/field/sport/{{ $sport->id }}" class="btn btn-primary">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- About End -->

@endsection

@section('scripts')


@endsection

