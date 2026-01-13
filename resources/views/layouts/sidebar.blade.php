<aside class="d-flex flex-column h-100">

    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <strong class="text-primary sidebar-title">MENU UTAMA</strong>

        <button id="toggleSidebar" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <ul class="nav nav-pills flex-column gap-1 p-2 sidebar-menu flex-fill">

        {{-- Menu Langsung --}}
        <li class="nav-item">
            <a href="#" class="nav-link active">
                <i class="fas fa-home me-2"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>

        {{-- Level 1 --}}
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#menuLevel1">
                <i class="fas fa-layer-group me-2"></i>
                <span class="menu-text">Menu Level 1</span>
                <i class="fas fa-chevron-down float-end menu-arrow"></i>
            </a>

            <div class="collapse ms-3" id="menuLevel1">
                <ul class="nav flex-column gap-1">

                    {{-- Level 2 --}}
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#menuLevel2">
                            <i class="fas fa-folder me-2"></i>
                            <span class="menu-text">Menu Level 2</span>
                        </a>

                        <div class="collapse ms-3" id="menuLevel2">
                            <ul class="nav flex-column gap-1">

                                {{-- Level 3 --}}
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-file-alt me-2"></i>
                                        <span class="menu-text">Menu Level 3 A</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-file-alt me-2"></i>
                                        <span class="menu-text">Menu Level 3 B</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    {{-- Level 2 langsung --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-circle me-2"></i>
                            <span class="menu-text">Menu Level 2 Langsung</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        {{-- Menu Langsung --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-cog me-2"></i>
                <span class="menu-text">Pengaturan</span>
            </a>
        </li>

        @foreach($menus as $menu)
            @if(!$menu->permission || auth()->user()->can($menu->permission))
                <li class="nav-item">
                    <a class="nav-link" href="{{ $menu->route ? route($menu->route) : '#' }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>

                    @if($menu->children->count())
                        <ul class="nav flex-column ms-3">
                            @foreach($menu->children as $child)
                                @can($child->permission)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route($child->route) }}">
                                            <i class="{{ $child->icon }}"></i>
                                            {{ $child->title }}
                                        </a>
                                    </li>
                                @endcan
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach

    </ul>

</aside>
