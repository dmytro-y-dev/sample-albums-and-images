App = App || {}

App.Loader = Marionette.Object.extend {
  initialize : (options) ->
    this.parentSelector = options.parentElement.selector
    this.isHidden = true

  show : () ->
    if !this.isHidden
      return

    this.isHidden = false

    $(this.parentSelector).append '<div class="loader">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
    </div>'

  hide : () ->
    if this.isHidden
      return

    this.isHidden = true

    $(this.parentSelector).children('.loader').remove()
}