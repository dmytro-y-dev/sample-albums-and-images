App = App || {}

# Application: Main application class

App.Application = Backbone.Marionette.Application.extend
  albumMaxImagesCount : 10
  websiteRoot : Routing.generate 'app_frontend_home'

  # Create application's main controller

  initializeController : () ->
    this.controller = new App.Controller()

  # Initialize Backbone routing

  initializeRouting : () ->
    websiteRoot = this.websiteRoot

    # Enable proper Backbone handling of <a> links

    $(document).on 'click', 'a', (event) ->
      fragment = ''

      if ($(this).attr('href').substring(0, websiteRoot.length) == websiteRoot)
        fragment = $(this).attr('href').substring(websiteRoot.length)

      matched = _.any Backbone.history.handlers, (handler) ->
        return handler.route.test(fragment)

      if (matched)
        event.preventDefault()
        Backbone.history.navigate(fragment, { trigger: true })

    # Start Backbone routing

    Backbone.history.start({pushState : true, hashChange: false, root: websiteRoot});

  # Run initialization procedures before application start

  onBeforeStart : () ->
    this.initializeController();
    this.initializeRouting();

$(() ->
  # Start application after DOM is ready

  window.app = new App.Application()
  window.app.start()
)