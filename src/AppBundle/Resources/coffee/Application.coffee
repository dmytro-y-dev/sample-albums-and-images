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

    if (window.navigator.standalone != true)
      $('body').addClass('no-standalone')

    $(document).on 'click', 'a', (event) ->
      href = ''

      if ($(this).attr('href').substring(0, window.app.websiteRoot.length) == window.app.websiteRoot)
        href = $(this).attr('href').substring(window.app.websiteRoot.length)

      fragment = Backbone.history.getFragment(href);
      matched = _.any Backbone.history.handlers, (handler) ->
        return handler.route.test(fragment)

      if (matched)
        event.preventDefault()
        Backbone.history.navigate(fragment, { trigger: true })

    Backbone.history.start({pushState : true, hashChange: false, root: window.app.websiteRoot});

# Start application after DOM is ready

$(() ->
  window.app = new App.Application()
  window.app.start()
)