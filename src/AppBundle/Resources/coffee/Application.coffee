App = App || {}

App.Application = Backbone.Marionette.Application.extend
  albumMaxImagesCount : 10

  createController : () ->
    return new App.Controller()

  onBeforeStart : () ->
    # Create application main controller

    window.app.controller = this.createController();

    # Initialize Backbone routing

    Backbone.history.start();

# Start application after DOM is ready

$(() ->
  window.app = new App.Application()
  window.app.start()
)