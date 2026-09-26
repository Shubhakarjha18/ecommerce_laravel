<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('admin')}}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cart-arrow-down"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    @can('view-dashboard')
    <li class="nav-item active">
        <a class="nav-link" href="{{route('admin')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endcan

    <!-- Divider -->
    @canany(['view-media', 'view-banner'])
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Banner
    </div>
    @endcanany

    <!-- Nav Item - Media Manager -->
    @can('view-media')
    <li class="nav-item">
        <a class="nav-link" href="{{route('file-manager')}}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Media Manager</span>
        </a>
    </li>
    @endcan

    <!-- Nav Item - Banners -->
    @can('view-banner')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-image"></i>
            <span>Banners</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Banner Options:</h6>
                <a class="collapse-item" href="{{route('banner.index')}}">Banners</a>
                @can('create-banner')
                <a class="collapse-item" href="{{route('banner.create')}}">Add Banners</a>
                @endcan
            </div>
        </div>
    </li>
    @endcan

    <!-- Shop Section Header -->
    @canany(['view-category', 'view-product', 'view-brand', 'view-shipping', 'view-order', 'view-review'])
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Shop
    </div>
    @endcanany

    <!-- Categories -->
    @can('view-category')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#categoryCollapse" aria-expanded="true" aria-controls="categoryCollapse">
            <i class="fas fa-sitemap"></i>
            <span>Category</span>
        </a>
        <div id="categoryCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Category Options:</h6>
                <a class="collapse-item" href="{{route('category.index')}}">Category</a>
                @can('create-category')
                <a class="collapse-item" href="{{route('category.create')}}">Add Category</a>
                @endcan
            </div>
        </div>
    </li>
    @endcan

    <!-- Products -->
    @can('view-product')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#productCollapse" aria-expanded="true" aria-controls="productCollapse">
            <i class="fas fa-cubes"></i>
            <span>Products</span>
        </a>
        <div id="productCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Product Options:</h6>
                <a class="collapse-item" href="{{route('product.index')}}">Products</a>
                @can('create-product')
                <a class="collapse-item" href="{{route('product.create')}}">Add Product</a>
                @endcan
            </div>
        </div>
    </li>
    @endcan

    <!-- Brands -->
    @can('view-brand')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#brandCollapse" aria-expanded="true" aria-controls="brandCollapse">
            <i class="fas fa-table"></i>
            <span>Brands</span>
        </a>
        <div id="brandCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Brand Options:</h6>
                <a class="collapse-item" href="{{route('brand.index')}}">Brands</a>
                @can('create-brand')
                <a class="collapse-item" href="{{route('brand.create')}}">Add Brand</a>
                @endcan
            </div>
        </div>
    </li>
    @endcan

    <!-- Shipping -->
    @can('view-shipping')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#shippingCollapse" aria-expanded="true" aria-controls="shippingCollapse">
            <i class="fas fa-truck"></i>
            <span>Shipping</span>
        </a>
        <div id="shippingCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Shipping Options:</h6>
                <a class="collapse-item" href="{{route('shipping.index')}}">Shipping</a>
                @can('create-shipping')
                <a class="collapse-item" href="{{route('shipping.create')}}">Add Shipping</a>
                @endcan
            </div>
        </div>
    </li>
    @endcan

    <!-- Orders -->
    @can('view-order')
    <li class="nav-item">
        <a class="nav-link" href="{{route('order.index')}}">
            <i class="fas fa-cart-plus"></i>
            <span>Orders</span>
        </a>
    </li>
    @endcan

    <!-- Reviews -->
    @can('view-review')
    <li class="nav-item">
        <a class="nav-link" href="{{route('review.index')}}">
            <i class="fas fa-comments"></i>
            <span>Reviews</span>
        </a>
    </li>
    @endcan

    <!-- Posts Section Header -->
    @canany(['view-post', 'view-post-category', 'view-post-tag', 'view-comment'])
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Posts
    </div>
    @endcanany

    <!-- Posts -->
    @can('view-post')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#postCollapse" aria-expanded="true" aria-controls="postCollapse">
            <i class="fas fa-fw fa-folder"></i>
            <span>Posts</span>
        </a>
        <div id="postCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Post Options:</h6>
                <a class="collapse-item" href="{{route('post.index')}}">Posts</a>
                @can('create-post')
                <a class="collapse-item" href="{{route('post.create')}}">Add Post</a>
                @endcan
            </div>
        </div>
    </li>
    @endcan

    <!-- Post Category -->
    @can('view-post-category')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#postCategoryCollapse" aria-expanded="true" aria-controls="postCategoryCollapse">
            <i class="fas fa-sitemap fa-folder"></i>
            <span>Category</span>
        </a>
        <div id="postCategoryCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Category Options:</h6>
                <a class="collapse-item" href="{{route('post-category.index')}}">Category</a>
                @can('create-post-category')
                <a class="collapse-item" href="{{route('post-category.create')}}">Add Category</a>
                @endcan
            </div>
        </div>
    </li>
    @endcan

    <!-- Post Tags -->
    @can('view-post-tag')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tagCollapse" aria-expanded="true" aria-controls="tagCollapse">
            <i class="fas fa-tags fa-folder"></i>
            <span>Tags</span>
        </a>
        <div id="tagCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tag Options:</h6>
                <a class="collapse-item" href="{{route('post-tag.index')}}">Tag</a>
                @can('create-post-tag')
                <a class="collapse-item" href="{{route('post-tag.create')}}">Add Tag</a>
                @endcan
            </div>
        </div>
    </li>
    @endcan

    <!-- Comments -->
    @can('view-comment')
    <li class="nav-item">
        <a class="nav-link" href="{{route('comment.index')}}">
            <i class="fas fa-comments fa-chart-area"></i>
            <span>Comments</span>
        </a>
    </li>
    @endcan

    <!-- General Settings Section Header -->
    @canany(['view-coupon', 'view-user', 'view-setting'])
    <hr class="sidebar-divider d-none d-md-block">
    <div class="sidebar-heading">
        General Settings
    </div>
    @endcanany

    <!-- Coupon -->
    @can('view-coupon')
    <li class="nav-item">
        <a class="nav-link" href="{{route('coupon.index')}}">
            <i class="fas fa-table"></i>
            <span>Coupon</span>
        </a>
    </li>
    @endcan

    <!-- Users -->
    @can('view-user')
    <li class="nav-item">
        <a class="nav-link" href="{{route('users.index')}}">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
    </li>
    @endcan

    <!-- General Settings -->
    @can('view-setting')
    <li class="nav-item">
        <a class="nav-link" href="{{route('settings')}}">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
    </li>
    @endcan

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
