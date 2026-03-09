        <aside id="layout-menu" class="layout-menu menu-vertical menu">
          <div class="app-brand demo ">
            <a href="{{ route('admin.index') }}" class="app-brand-link">
              <span class="app-brand-logo demo">
                <span class="text-primary">
                  <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                      fill="currentColor" />
                    <path
                      opacity="0.06"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                      fill="#161616" />
                    <path
                      opacity="0.06"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                      fill="#161616" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                      fill="currentColor" />
                  </svg>
                </span>
              </span>
              <span class="app-brand-text demo menu-text fw-bold ms-3">Vuexy</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
              <i class="icon-base ti tabler-x d-block d-xl-none"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Tổng quan -->
            <li class="menu-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
              <a href="{{ route('admin.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-smart-home"></i>
                <div data-i18n="Tổng quan">Tổng quan</div>
              </a>
            </li>
            <!-- Chấm công -->
            @php $menuChamCongOpen = request()->routeIs('admin.diem-danh.*'); @endphp
            <li class="menu-item {{ $menuChamCongOpen ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-clock"></i>
                <div data-i18n="Chấm công">Chấm công</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.diem-danh.diem-danh') ? 'active' : '' }}">
                  <a href="{{ route('admin.diem-danh.diem-danh') }}" class="menu-link">
                    <div data-i18n="Điểm danh">Điểm danh</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.diem-danh.cham-cong') ? 'active' : '' }}">
                  <a href="{{ route('admin.diem-danh.cham-cong') }}" class="menu-link">
                    <div data-i18n="Chấm công">Chấm công</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Nhân sự -->
            @php $menuNhanSuOpen = request()->routeIs('admin.nhan-su.*'); @endphp
            <li class="menu-item {{ $menuNhanSuOpen ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="Nhân sự">Nhân sự</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.nhan-su.danh-sach') ? 'active' : '' }}">
                  <a href="{{ route('admin.nhan-su.danh-sach') }}" class="menu-link">
                    <div data-i18n="Danh sách nhân sự">Danh sách nhân sự</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.nhan-su.phan-quyen') ? 'active' : '' }}">
                  <a href="{{ route('admin.nhan-su.phan-quyen') }}" class="menu-link">
                    <div data-i18n="Phân quyền">Phân quyền</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.nhan-su.lich-lam-viec') ? 'active' : '' }}">
                  <a href="{{ route('admin.nhan-su.lich-lam-viec') }}" class="menu-link">
                    <div data-i18n="Lịch làm việc">Lịch làm việc</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Khách hàng -->
            @php $menuKhachHangOpen = request()->is('admin/khach-hang*'); @endphp
            <li class="menu-item {{ $menuKhachHangOpen ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-user-circle"></i>
                <div data-i18n="Khách hàng">Khách hàng</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/khach-hang/danh-sach') ? 'active' : '' }}">
                  <a href="{{ url('admin/khach-hang/danh-sach') }}" class="menu-link">
                    <div data-i18n="Danh sách khách hàng">Danh sách khách hàng</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->is('admin/khach-hang/hop-dong') ? 'active' : '' }}">
                  <a href="{{ url('admin/khach-hang/hop-dong') }}" class="menu-link">
                    <div data-i18n="Hợp đồng">Hợp đồng</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Tài chính kế toán -->
            @php $menuTaiChinhOpen = request()->is('admin/tai-chinh*'); @endphp
            <li class="menu-item {{ $menuTaiChinhOpen ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-cash"></i>
                <div data-i18n="Tài chính kế toán">Tài chính kế toán</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/tai-chinh/cong-no') ? 'active' : '' }}">
                  <a href="{{ url('admin/tai-chinh/cong-no') }}" class="menu-link">
                    <div data-i18n="Công nợ">Công nợ</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->is('admin/tai-chinh/phieu-thu-chi') ? 'active' : '' }}">
                  <a href="{{ url('admin/tai-chinh/phieu-thu-chi') }}" class="menu-link">
                    <div data-i18n="Phiếu thu chi">Phiếu thu chi</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->is('admin/tai-chinh/tinh-luong') ? 'active' : '' }}">
                  <a href="{{ url('admin/tai-chinh/tinh-luong') }}" class="menu-link">
                    <div data-i18n="Tính lương">Tính lương</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Trang phục -->
            @php $menuTrangPhucOpen = request()->is('admin/trang-phuc*'); @endphp
            <li class="menu-item {{ $menuTrangPhucOpen ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-shirt"></i>
                <div data-i18n="Trang phục">Trang phục</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/trang-phuc/san-pham') ? 'active' : '' }}">
                  <a href="{{ url('admin/trang-phuc/san-pham') }}" class="menu-link">
                    <div data-i18n="Sản phẩm">Sản phẩm</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->is('admin/trang-phuc/kho-hang') ? 'active' : '' }}">
                  <a href="{{ url('admin/trang-phuc/kho-hang') }}" class="menu-link">
                    <div data-i18n="Kho hàng">Kho hàng</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->is('admin/trang-phuc/hop-dong') ? 'active' : '' }}">
                  <a href="{{ url('admin/trang-phuc/hop-dong') }}" class="menu-link">
                    <div data-i18n="Hợp đồng">Hợp đồng</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Dịch vụ -->
            @php $menuDichVuOpen = request()->routeIs('admin.dich-vu.*'); @endphp
            <li class="menu-item {{ $menuDichVuOpen ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-briefcase"></i>
                <div data-i18n="Dịch vụ">Dịch vụ</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.dich-vu.dich-vu-le') ? 'active' : '' }}">
                  <a href="{{ route('admin.dich-vu.dich-vu-le') }}" class="menu-link">
                    <div data-i18n="Dịch vụ lẻ">Dịch vụ lẻ</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.dich-vu.nhom-dich-vu') ? 'active' : '' }}">
                  <a href="{{ route('admin.dich-vu.nhom-dich-vu') }}" class="menu-link">
                    <div data-i18n="Nhóm dịch vụ">Nhóm dịch vụ</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Thông tin cá nhân -->
            <li class="menu-item {{ request()->routeIs('admin.thong-tin-ca-nhan') ? 'active' : '' }}">
              <a href="{{ route('admin.thong-tin-ca-nhan') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-user-circle"></i>
                <div data-i18n="Thông tin cá nhân">Thông tin cá nhân</div>
              </a>
            </li>
          </ul>
        </aside>

        <div class="menu-mobile-toggler d-xl-none rounded-1">
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
            <i class="ti tabler-menu icon-base"></i>
            <i class="ti tabler-chevron-right icon-base"></i>
          </a>
        </div>
