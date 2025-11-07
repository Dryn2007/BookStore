# TODO: Add Photo Functionality to Reviews

## Step 1: Create Migration

-   [x] Create new migration to add 'photos' JSON column to reviews table

## Step 2: Update Review Model

-   [x] Add 'photos' to fillable array in app/Models/Review.php

## Step 3: Update ReviewController

-   [x] Add 'photos' validation (array, max 5 images, image types, 2MB max per image)
-   [x] Handle file uploads in store() method: store in storage/app/public/reviews/{review_id}/, save JSON paths
-   [x] Handle file uploads in update() method: store new photos, delete removed photos from storage

## Step 4: Update reviews.blade.php

-   [x] Add multiple file input to the form
-   [x] Add JavaScript for photo preview and delete functionality
-   [x] Show existing photos on edit with delete option
-   [x] Update review display: show first 2 images + blurred "+X more" if more than 2
-   [x] Add modal popup on click (gallery view with all photos, rating, comment)

## Step 5: Update product-detail.blade.php

-   [x] Update review display to include photos as in reviews.blade.php

## Followup Steps

-   [x] Run php artisan migrate to add photos column
-   [x] Test: upload multiple photos, preview/delete, display first 2 + blurred, popup gallery, edit functionality
