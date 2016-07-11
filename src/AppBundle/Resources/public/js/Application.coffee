App = App || {}

App.Application = Backbone.Marionette.Application.extend
  albumMaxImagesCount : 10

  createRootLayout : () ->
    this.root = new App.RootLayout()
    this.root.render()

    this.controller.renderAlbums()
    this.controller.renderAlbumsWithMaxImages()

  createController : () ->
    this.controller = new App.Controller()

  createRouter : (controller) ->
    controller.router = new Marionette.AppRouter
      controller : controller
      appRoutes:
        "" : "albums"
        "album/:id" : "imagesPaginatedFirstPage"
        "album/:id/page/:page" : "imagesPaginatedSpecificPage"

# Start application after DOM is complete

$(() ->
  window.app = new App.Application()

  window.app.on 'before:start', () ->
    # Initialize application and Backbone stuff

    window.app.createController();
    window.app.createRouter(window.app.controller);

    window.app.createRootLayout();

    Backbone.history.start();

    # Initialize masonry grid

    $('.grid').masonry
      itemSelector : '.grid-item',
      columnWidth : 200

  window.app.start()
)