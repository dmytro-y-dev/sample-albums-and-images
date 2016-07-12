App = App || {}

App.Application = Backbone.Marionette.Application.extend
  albumMaxImagesCount : 10
  websiteRoot : Routing.generate 'app_frontend_home'

  createController : () ->
    return new App.Controller()

  onBeforeStart : () ->
    # Create application main controller

    this.controller = this.createController();

    # Initialize Backbone routing

    websiteRoot = this.websiteRoot

    $(document).on 'click', 'a', (event) ->
      fragment = ''

      if ($(this).attr('href').substring(0, websiteRoot.length) == websiteRoot)
        fragment = $(this).attr('href').substring(websiteRoot.length)

      matched = _.any Backbone.history.handlers, (handler) ->
        return handler.route.test(fragment)

      if (matched)
        event.preventDefault()
        Backbone.history.navigate(fragment, { trigger: true })

    Backbone.history.start({pushState : true, hashChange: false, root: websiteRoot});

# Start application after DOM is ready

$(() ->
  window.app = new App.Application()
  window.app.start()
)