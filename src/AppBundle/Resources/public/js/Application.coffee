App = App || {}

App.Application = Backbone.Marionette.Application.extend
  createRootLayout : () ->
    this.root = new App.RootLayout()
    this.root.render()
    this.root.showSidebar()

  createRouter : () ->
    this.controller = new App.Controller()
    this.router = new Marionette.AppRouter
      controller : this.controller
      appRoutes:
        "" : "albums"
        "album/:id" : "imagesPaginatedFirstPage"
        "album/:id/page/:page" : "imagesPaginatedSpecificPage"

# Start application after DOM is complete

$(() ->
  app = new App.Application()

  app.on 'before:start', () ->
    # Initialize Marionette and Backbone stuff

    app.createRootLayout();
    app.createRouter();

    Backbone.history.start();

    # Initialize masonry grid

    $('.grid').masonry
      itemSelector : '.grid-item',
      columnWidth : 200

  app.start()
)