SPATIE MEDIA LIBRARY INTEGRATION - CHANGED FILES
=================================================

All image handling now goes through spatie/laravel-medialibrary:

UPLOAD (backend panel)
  Every create/edit form posts a native <input type="file"> and the
  controllers store the file with explicit Spatie calls:
      $model->addMedia($request->file('photo'))->toMediaCollection('photo');
  Files live in storage/app/public/{model}/{media_id}/, conversions in
  .../conversions/, and every association is tracked in the `media` table.

DISPLAY (frontend + backend + user area)
  Every <img> now reads the media library through the Spatie API:
      $model->hasMedia('photo') ? $model->getFirstMediaUrl('photo') : ...
      $model->getFirstMediaUrl('photo', 'thumb')            (150x150 thumb)
  The old $model->photo value is only used as a fallback for legacy rows
  that were never imported, so nothing breaks before you run step 5.

NEW FILES
---------
app/Traits/HasMediaPhoto.php        Shared trait: photo media collection
                                    (singleFile), 150x150 thumb conversion,
                                    and a photo accessor with legacy-column
                                    fallback.
app/Console/Commands/AttachLegacyMedia.php
                                    artisan media:attach-legacy [--dry-run]
                                    associates existing legacy image files
                                    (photo/logo columns) with Spatie.
README-spatie-media.txt             This file.

MODIFIED FILES
--------------
app/Models/Banner.php               implements HasMedia (trait added)
app/Models/Category.php             implements HasMedia (trait added)
app/Models/Product.php              implements HasMedia (trait added)
app/Models/Post.php                 implements HasMedia (trait added)
app/Models/Settings.php             implements HasMedia; logo collection
                                    + logo accessor
app/Models/Message.php              implements HasMedia; photo collection for
                                    the sender-avatar snapshot
app/User.php                        implements HasMedia (trait added)

app/Http/Controllers/BannerController.php
app/Http/Controllers/CategoryController.php
app/Http/Controllers/PostController.php
app/Http/Controllers/ProductController.php
app/Http/Controllers/UsersController.php
app/Http/Controllers/AdminController.php   (profile photo + settings photo/logo)
app/Http/Controllers/HomeController.php
app/Http/Controllers/MessageController.php (avatar snapshot via media API)
    -> validation is now image|mimes:jpg,jpeg,png,gif,webp|max:4096
    -> $request->except('photo') (and 'logo' for settings)
    -> explicit Spatie association after save:
       $model->addMedia($request->file('photo'))->toMediaCollection('photo');

FRONTEND VIEWS - explicit Spatie display
----------------------------------------
resources/views/frontend/index.blade.php
    banners, categories, featured/hot/latest products, blog, quickview
resources/views/frontend/layouts/header.blade.php
    site logo (logo collection) + cart/wishlist product thumbnails
resources/views/frontend/pages/product-grids.blade.php
resources/views/frontend/pages/product-lists.blade.php
resources/views/frontend/pages/product_detail.blade.php
    og:image, gallery, related products, reviewer avatars
resources/views/frontend/pages/blog.blade.php
resources/views/frontend/pages/blog-detail.blade.php
    post images + recent-post widgets
resources/views/frontend/pages/about-us.blade.php
resources/views/frontend/pages/cart.blade.php
resources/views/frontend/pages/wishlist.blade.php
resources/views/frontend/pages/comment.blade.php
    commenter avatars

BACKEND + USER-AREA VIEWS - explicit Spatie display
----------------------------------------------------
resources/views/backend/banner/index.blade.php
resources/views/backend/category/index.blade.php
resources/views/backend/post/index.blade.php
resources/views/backend/product/index.blade.php
resources/views/backend/users/index.blade.php
resources/views/user/users/index.blade.php
    -> list thumbnails via getFirstMediaUrl('photo', 'thumb')
resources/views/backend/layouts/header.blade.php
resources/views/user/layouts/header.blade.php
    -> topbar avatar via hasMedia('photo')/getFirstMediaUrl('photo')
resources/views/backend/message/message.blade.php
resources/views/backend/message/show.blade.php
    -> message avatars via media API
resources/views/backend/banner/edit.blade.php
resources/views/backend/category/edit.blade.php
resources/views/backend/post/edit.blade.php
resources/views/backend/product/edit.blade.php
resources/views/backend/users/edit.blade.php
resources/views/backend/users/profile.blade.php
resources/views/backend/setting.blade.php     (logo + photo previews)
resources/views/user/users/profile.blade.php
    -> current-image previews via media API

FORMS CONVERTED FROM LARAVEL FILEMANAGER TO FILE UPLOADS
---------------------------------------------------------
resources/views/backend/banner/create.blade.php
resources/views/backend/category/create.blade.php
resources/views/backend/post/create.blade.php
resources/views/backend/product/create.blade.php
resources/views/backend/users/create.blade.php
resources/views/user/users/create.blade.php
resources/views/user/users/edit.blade.php
resources/views/user/setting.blade.php
    -> native <input type="file"> + enctype="multipart/form-data"
       + FileReader live preview; LFM script tags removed.

resources/views/backend/brand/create.blade.php
resources/views/backend/brand/edit.blade.php
resources/views/backend/shipping/create.blade.php
resources/views/backend/shipping/edit.blade.php
resources/views/backend/coupon/create.blade.php
resources/views/backend/coupon/edit.blade.php
    -> removed dead Laravel Filemanager script includes (no image fields).

HOW TO APPLY (on another copy of this project)
----------------------------------------------
1. Copy the `app/` and `resources/` folders from this package over your
   project root, replacing the existing files (structure is preserved).
2. Requirements already in your composer.json: spatie/laravel-medialibrary.
   If missing: composer require spatie/laravel-medialibrary
3. Run the migration if the media table does not exist yet:
   php artisan migrate
4. Make sure the public disk symlink exists:
   php artisan storage:link
5. To associate pre-existing image files with Spatie:
   php artisan media:attach-legacy --dry-run   (preview)
   php artisan media:attach-legacy             (apply)
6. Clear caches: php artisan config:clear && php artisan view:clear

NOTES
-----
- APP_URL in .env controls the generated media URLs.
- The media library generates absolute URLs from APP_URL; per-collection
  conversions ("thumb", 150x150) are created non-queued on upload.
- Old rows whose photo column still points at /storage/photos/1/... keep
  displaying through the legacy fallback until imported (step 5).
