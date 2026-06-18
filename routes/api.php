<?php

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\ResourceController as AdminResourceController;
use App\Http\Controllers\Api\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/settings', [SettingController::class, 'show']);
    Route::get('/home', [HomeController::class, 'index']);

    Route::get('/about', [PublicController::class, 'about']);
    Route::get('/contact', [PublicController::class, 'contact']);
    Route::post('/contact', [PublicController::class, 'contactSubmit']);
    Route::post('/contact/quick', [PublicController::class, 'quickContact']);
    Route::get('/faqs', [PublicController::class, 'faqs']);
    Route::get('/terms', [PublicController::class, 'terms']);
    Route::get('/privacy', [PublicController::class, 'privacy']);
    Route::get('/team-members', [PublicController::class, 'teamMembers']);
    Route::get('/team-members/{slug}', [PublicController::class, 'teamMember']);
    Route::get('/blog', [PublicController::class, 'blog']);
    Route::get('/posts/{slug}', [PublicController::class, 'post']);
    Route::get('/categories/{slug}', [PublicController::class, 'category']);
    Route::get('/destinations', [PublicController::class, 'destinations']);
    Route::get('/destinations/{slug}', [PublicController::class, 'destination']);
    Route::get('/packages', [PublicController::class, 'packages']);
    Route::get('/packages/{slug}', [PublicController::class, 'package']);
    Route::post('/subscribers', [PublicController::class, 'subscriberSubmit']);
    Route::post('/enquiries/{id}', [PublicController::class, 'enquirySubmit']);

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
    Route::get('/user', [UserApiController::class, 'user']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        Route::get('/user/dashboard', [UserApiController::class, 'dashboard']);
        Route::get('/user/bookings', [UserApiController::class, 'bookings']);
        Route::get('/user/invoices/{invoiceNo}', [UserApiController::class, 'invoice']);
        Route::get('/user/reviews', [UserApiController::class, 'reviews']);
        Route::post('/user/reviews', [UserApiController::class, 'submitReview']);
        Route::get('/user/wishlist', [UserApiController::class, 'wishlist']);
        Route::post('/user/wishlist/{packageId}', [UserApiController::class, 'addWishlist']);
        Route::delete('/user/wishlist/{id}', [UserApiController::class, 'deleteWishlist']);
        Route::get('/user/profile', [UserApiController::class, 'profile']);
        Route::post('/user/profile', [UserApiController::class, 'updateProfile']);
        Route::get('/user/messages', [UserApiController::class, 'messages']);
        Route::post('/user/messages/start', [UserApiController::class, 'startMessage']);
        Route::post('/user/messages', [UserApiController::class, 'submitMessage']);
        Route::get('/user/messages/poll', [UserApiController::class, 'messagePoll']);
        Route::get('/user/messages/count', [UserApiController::class, 'messageNotificationCount']);
        Route::post('/user/bookings', [UserApiController::class, 'bookingPayment']);
    });

    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);

        Route::middleware(['auth:sanctum', 'api.admin'])->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout']);
            Route::get('/me', [AdminAuthController::class, 'me']);
            Route::post('/profile', [AdminAuthController::class, 'updateProfile']);

            Route::get('/dashboard', [AdminDashboardController::class, 'index']);
            Route::get('/notifications', [AdminDashboardController::class, 'notificationsPoll']);
            Route::post('/notifications/bookings/viewed', [AdminDashboardController::class, 'markBookingViewed']);
            Route::post('/notifications/users/viewed', [AdminDashboardController::class, 'markUserViewed']);
            Route::post('/notifications/reviews/viewed', [AdminDashboardController::class, 'markReviewViewed']);

            Route::get('/bookings', [AdminResourceController::class, 'bookings']);
            Route::get('/messages', [AdminResourceController::class, 'messages']);
            Route::get('/messages/{id}', function (int $id) {
                return app(AdminResourceController::class)->show('messages', $id);
            });

            Route::post('/destinations/{id}/photos', [AdminResourceController::class, 'storeDestinationPhoto']);
            Route::delete('/destinations/{id}/photos/{photoId}', [AdminResourceController::class, 'destroyDestinationPhoto']);
            Route::post('/destinations/{id}/videos', [AdminResourceController::class, 'storeDestinationVideo']);
            Route::delete('/destinations/{id}/videos/{videoId}', [AdminResourceController::class, 'destroyDestinationVideo']);

            Route::post('/packages/{id}/photos', [AdminResourceController::class, 'storePackagePhoto']);
            Route::delete('/packages/{id}/photos/{photoId}', [AdminResourceController::class, 'destroyPackagePhoto']);
            Route::post('/packages/{id}/videos', [AdminResourceController::class, 'storePackageVideo']);
            Route::delete('/packages/{id}/videos/{videoId}', [AdminResourceController::class, 'destroyPackageVideo']);
            Route::post('/packages/{id}/amenities', [AdminResourceController::class, 'storePackageAmenity']);
            Route::delete('/packages/{id}/amenities/{amenityId}', [AdminResourceController::class, 'destroyPackageAmenity']);
            Route::post('/packages/{id}/itineraries', [AdminResourceController::class, 'storePackageItinerary']);
            Route::delete('/packages/{id}/itineraries/{itineraryId}', [AdminResourceController::class, 'destroyPackageItinerary']);
            Route::post('/packages/{id}/faqs', [AdminResourceController::class, 'storePackageFaq']);
            Route::delete('/packages/{id}/faqs/{faqId}', [AdminResourceController::class, 'destroyPackageFaq']);

            Route::delete('/destinations/{id}/force', [AdminResourceController::class, 'forceDestroyDestination']);
            Route::delete('/packages/{id}/force', [AdminResourceController::class, 'forceDestroyPackage']);
            Route::delete('/tours/{id}/force', [AdminResourceController::class, 'forceDestroyTour']);
            Route::delete('/users/{id}/force', [AdminResourceController::class, 'forceDestroyUser']);
            Route::post('/users/{userId}/message-thread', [AdminResourceController::class, 'messageThreadForUser']);
            Route::get('/contact/offices/{id}', [AdminResourceController::class, 'showContactOffice']);
            Route::post('/contact/offices', [AdminResourceController::class, 'storeContactOffice']);
            Route::put('/contact/offices/{id}', [AdminResourceController::class, 'updateContactOffice']);
            Route::delete('/contact/offices/{id}', [AdminResourceController::class, 'destroyContactOffice']);
            Route::get('/tours/{tourId}/bookings/{packageId}', [AdminResourceController::class, 'tourBookings']);
            Route::get('/tour-invoices/{invoiceNo}', [AdminResourceController::class, 'tourInvoice']);
            Route::delete('/tour-bookings/{bookingId}', [AdminResourceController::class, 'destroyTourBooking']);
            Route::post('/tour-bookings/{bookingId}/approve', [AdminResourceController::class, 'approveTourBooking']);

            Route::get('/subscribers/active-count', [AdminSubscriberController::class, 'activeCount']);
            Route::post('/subscribers/send-email', [AdminSubscriberController::class, 'sendEmail']);
            Route::post('/subscribers/bulk-delete', [AdminSubscriberController::class, 'bulkDelete']);

            Route::get('/singleton/{key}', [AdminResourceController::class, 'singleton']);
            Route::post('/singleton/{key}', [AdminResourceController::class, 'updateSingleton']);

            Route::get('/{resource}', [AdminResourceController::class, 'index']);
            Route::get('/{resource}/{id}', [AdminResourceController::class, 'show']);
            Route::post('/{resource}', [AdminResourceController::class, 'store']);
            Route::put('/{resource}/{id}', [AdminResourceController::class, 'update']);
            Route::delete('/{resource}/{id}', [AdminResourceController::class, 'destroy']);

            Route::post('/reviews/{id}/approve', [AdminResourceController::class, 'approveReview']);
            Route::post('/reviews/{id}/reject', [AdminResourceController::class, 'rejectReview']);
        });
    });
});
