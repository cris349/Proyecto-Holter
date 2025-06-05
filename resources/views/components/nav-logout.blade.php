<li class="nav-item mx-3 dropdown pe-2 d-flex align-items-center">
    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-user cursor-pointer text-danger"></i>
    </a>
    <ul class="dropdown-menu  dropdown-menu-end px-2 me-sm-n4"
        aria-labelledby="dropdownMenuButton">
        <li class="mb-2">
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <a class="nav-link cursor-pointer" @click.prevent="$root.submit();">
                    <div class="d-flex py-1">
                        <div class="my-auto">
                            <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-1">
                                {{ Auth::user()->name }}
                                <span class="nav-link-text ms-1 font-weight-bold">Cerrar sesión</span>
                            </h6>
                        </div>
                    </div>
                </a>
            </form>
        </li>

    </ul>
</li>