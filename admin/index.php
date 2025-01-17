<?php

include "../model/pdo.php";
include "../model/danhmuc.php";
include "../model/sanpham.php";
include "../model/taikhoan.php";
include "../model/binhluan.php";
include "../model/cart.php";
include "header.php";

if ((isset($_GET['act'])) && ($_GET['act'] != "")) {
    $act = $_GET['act'];
    switch ($act) {
            /*-----------------------------CRUD DANH MỤC------------------------- */
        case 'listdm':
            $listdanhmuc = loadall_danhmuc();
            include "danhmuc/list.php";
            break;
        case 'xoadm':
            if (isset($_GET['id_dm']) && $_GET['id_dm'] > 0) {
                delete_danhmuc($_GET['id_dm']);
            }
            $listdanhmuc = loadall_danhmuc();
            include "danhmuc/list.php";
            break;
        case 'adddm':
            if (isset($_POST['themmoi'])) {
                $tenloai = $_POST['tenloai'];
                $trangthai = '0';

                insert_danhmuc(NULL, $tenloai, $trangthai);

                $thongbao = "Thêm thành công";
                header("location:index.php?act=listdm");
                exit();
            }
            include "danhmuc/add.php";
            break;

        case 'suadm':
            if (isset($_POST['capnhat'])) {
                $id_dm = $_POST['id_dm'];
                $tenloai = $_POST['tenloai'];
                $trangthai = $_POST['trangthai'];

                update_danhmuc($id_dm, $tenloai, $trangthai);

                $thongbao = "Cập nhật thành công";
                header("location: index.php?act=listdm");
                exit();
            } elseif (isset($_GET['id'])) {
                $id_dm = $_GET['id'];
                $danhmuc = loadone_danhmuc($id_dm);
            }
            include "danhmuc/update.php";
            break;

            /*-----------------------------CRUD DANH MỤC------------------------- */

            /*-----------------------------CRUD SẢN PHẨM------------------------- */
            /// Sản phẩm
        case 'listsp':
            $kyw = isset($_POST['kyw']) ? $_POST['kyw'] : '';
            $id_dm = isset($_POST['id_dm']) ? $_POST['id_dm'] : 0;
            $listdanhmuc = loadall_danhmuc();
            $listsanpham = loadall_sanpham($kyw, $id_dm);
            include "sanpham/list.php";
            break;

        case 'xoasp':
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                delete_sanpham($_GET['id']);
            }
            $listsanpham = loadall_sanpham("", 0);
            include "sanpham/list.php";
            break;
        case 'addsp':
            if (isset($_POST['themmoi'])) {
                $tensp = isset($_POST['ten_sp']) ? $_POST['ten_sp'] : '';
                $giasp = isset($_POST['gia']) ? $_POST['gia'] : '';
                $hinh = isset($_FILES['anh']['name']) ? $_FILES['anh']['name'] : '';
                $mota = isset($_POST['mo_ta']) ? $_POST['mo_ta'] : '';
                $id_dm = isset($_POST['id_dm']) ? $_POST['id_dm'] : '';
                $so_luong = isset($_POST['so_luong']) ? $_POST['so_luong'] : 0; // Thêm số lượng
                $trangthai = isset($_POST['trangthai']) ? $_POST['trangthai'] : '';

                $target_dir = "../upload/";
                $target_file = $target_dir . basename($_FILES["anh"]["name"]);

                if ($_FILES['anh']['error'] === UPLOAD_ERR_OK) {
                    if (move_uploaded_file($_FILES["anh"]["tmp_name"], $target_file)) {
                        insert_sanpham($tensp, $giasp, $hinh, $mota, $id_dm, $so_luong, $trangthai); // Cập nhật hàm insert
                        $thongbao = "Thêm thành công";
                    } else {
                        $thongbao = "Lỗi khi upload file";
                    }
                } else {
                    $thongbao = "Lỗi khi upload file: " . $_FILES['anh']['error'];
                }

                // Lấy danh sách sản phẩm mới nhất để hiển thị
                $listdanhmuc = loadall_danhmuc();
                $listsanpham = loadall_sanpham("", 0); // Lấy toàn bộ sản phẩm

                // Hiển thị thông báo và danh sách sản phẩm
                include "sanpham/list.php";
                break;
            }
            $listdanhmuc = loadall_danhmuc();
            include "sanpham/add.php";
            break;



            error_log(print_r($listsanpham, true));

            // Các phần mã khác của bạn ở đây

            // Xử lý các hành động
            // switch ($act) {
            //     case 'addsp':
            //         if (isset($_POST['themmoi'])) {
            //             $id_dm = isset($_POST['id_dm']) ? $_POST['id_dm'] : null;
            //             $tensp = isset($_POST['ten_sp']) ? $_POST['ten_sp'] : '';
            //             $giasp = isset($_POST['gia']) ? $_POST['gia'] : '';
            //             $mota = isset($_POST['mota']) ? $_POST['mota'] : '';
            //             $hinh = isset($_FILES['hinh']['name']) ? $_FILES['hinh']['name'] : '';
            //             $trangthai = isset($_POST['trangthai']) ? $_POST['trangthai'] : '';

            //             $target_dir = "../upload/";
            //             $target_file = $target_dir . basename($_FILES["hinh"]["name"]);

            // if ($_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
            //     if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
            //         insert_sanpham($tensp, $giasp, $hinh, $mota, $id_dm, $trangthai);
            //         $thongbao = "Thêm thành công";
            //     } else {
            //         $thongbao = "Lỗi khi upload file";
            //     }
            // } else {
            //     $thongbao = "Lỗi khi upload file: " . $_FILES['hinh']['error'];
            // }
            //         }
            //         $listdanhmuc = loadall_danhmuc();
            //         include "sanpham/add.php";
            //         break;

            // Các phần mã xử lý khác



            // case 'listsp':
            //     $kyw = isset($_POST['kyw']) ? $_POST['kyw'] : '';
            //     $id_dm = isset($_POST['id_dm']) ? $_POST['id_dm'] : 0;
            //     $listdanhmuc = loadall_danhmuc();
            //     $listsanpham = loadall_sanpham($kyw, $id_dm);
            //     include "sanpham/list.php";
            //     break;
            // case 'xoasp':
            //     if (isset($_GET['id']) && $_GET['id'] > 0) {
            //         delete_sanpham($_GET['id']);
            //     }
            //     $listsanpham = loadall_sanpham("", 0);
            //     include "sanpham/list.php";
            //     break;
            // case 'updatesp':
            //     if (isset($_GET['id']) && $_GET['id'] > 0) {
            //         $sanpham = loadone_sanpham($_GET['id']);
            //     }
            //     if (isset($_POST['capnhat'])) {
            //         $id = isset($_POST['id']) ? $_POST['id'] : null;
            //         $iddm = isset($_POST['iddm']) ? $_POST['iddm'] : null;
            //         $tensp = isset($_POST['tensp']) ? $_POST['tensp'] : '';
            //         $giasp = isset($_POST['giasp']) ? $_POST['giasp'] : '';
            //         $mota = isset($_POST['mota']) ? $_POST['mota'] : '';
            //         $hinh = isset($_FILES['hinh']['name']) ? $_FILES['hinh']['name'] : '';
            //         $giamgia = isset($_POST['giamgia']) ? $_POST['giamgia'] : '';

            //         $target_dir = "../upload/";
            //         $target_file = $target_dir . basename($_FILES["hinh"]["name"]);
            //         if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
            //             update_sanpham($id, $iddm, $tensp, $giasp, $mota, $hinh, $giamgia);
            //             $thongbao = "Cập nhật thành công";
            //         } else {
            //             $thongbao = "Lỗi khi upload file";
            //         }
            //     }
            //     $listdanhmuc = loadall_danhmuc();
            //     include "sanpham/update.php";
            //     break;
            // default:
            //     include "home.php";
            //     break;
    }
} else {
    include "home.php";
}

// case 'updatesp':

//     $listdanhmuc = loadall_danhmuc();
//     $listsanpham = loadall_sanpham();
//     include "sanpham/list.php";
//     break;
// case 'taikhoan':
//     $listtaikhoan = loadall_taikhoan();
//     include "taikhoan/list.php";
//     break;
// case 'xoatk':
//     if (isset($_GET['id']) && ($_GET['id'] > 0)) {
//         delete_taikhoan($_GET['id']);
//     }
//     $listtaikhoan = loadall_taikhoan();
//     include "taikhoan/list.php";
//     break;
// case 'dsbl':
//     $listbinhluan = loadall_binhluan(0);
//     include "binhluan/binhluan.php";
//     break;
// case 'xoabl':
//     if (isset($_GET['id']) && ($_GET['id'] > 0)) {
//         delete_binhluan($_GET['id']);
//     }
//     $listbinhluan = loadall_binhluan(0);
//     include "binhluan/binhluan.php";
//     break;



// case 'donhang': {
//         $listbill = loadall_bill();
//         include 'donhang/listdh.php';
//         break;
//     }
// case 'ttdh': {
//         if (isset($_GET['iddh']) && ($_GET['iddh'])) {
//             $bill = loadone_bill($_GET['iddh']);
//         }
//         if (isset($_POST['btnsub'])) {
//             $iddh = $_POST['iddh'];
//             $ttdh = $_POST['ttdh'];
//             thaydoi_trangthai($ttdh, $iddh);
//             header('location: ?act=donhang');
//         }
//         include 'donhang/ttdh.php';
//         break;
//     }
// case 'thongke':
//     $listthongke = loadall_thongke();
//     include "thongke.php";
//     break;
// case 'bieudo':
//     if (isset($_POST['btnsub'])) {
//         $listthongke = loadall_thongke();
//     }

//     if (isset($_POST['tk_donhang'])) {
//         $listthongke_thang = load_thongke_sanpham_donhang();
//     }

//     if (isset($_GET['type']) && $_GET['type'] == 'month') {
//         $listthongke_thang = load_thongke_sanpham_donhang();
//     } else if (isset($_GET['type']) && $_GET['type'] == 'week') {
//         $listthongke_tuan = load_thongke_sanpham_donhang_tuan();
//     } else if (isset($_GET['type']) && $_GET['type'] == 'day') {
//         $listthongke_ngay = load_thongke_sanpham_donhang_ngay();
//         //                                 echo "<pre>";
//         // print_r($listthongke_ngay);
//         // die;
//     } else {
//         $listthongke_thang = load_thongke_sanpham_donhang();
//     }

//     include "bieudo.php";
//     break;
// default:
//     include "home.php";
//     break;
