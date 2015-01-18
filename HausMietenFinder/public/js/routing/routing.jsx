$.extend(defineNamespace("Components.App"), {
  Body: React.createClass({
    render: function () {
      return (
        <div>
          <Components.App.Header/>
          <div id="main-content">
            <RouteHandler/>
          </div>
          <Components.App.Footer/>
        </div>
      );
    }
  }),

  Header: React.createClass({
    render: function() {
      return (
        <header>
          <div className="container">
            <Link className="home-link" to="default" params={{splat:''}}>HausMietenFinder</Link>
          </div>
        </header>
      );
    }
  }),

  Footer: React.createClass({
    render: function() {
      return (
        <footer>
          <div className="container">
            <Link to="default" params={{splat:'content/about'}}>About</Link>
          </div>
        </footer>
      );
    }
  }),

  MainRouteHandler: React.createClass({
    
    mixins: [ReactRouter.State],

    onDOMRender: function() {

      Helpers.UI.DOM.GetBody().trigger('new_routing', {
        controller: this.controller,
        action: this.action,
        extra_properties: this.extra_properties,
        params: this.route_params
      });
    },

    componentDidUpdate: function() {
      this.onDOMRender();
    },

    componentDidMount: function() {
      this.onDOMRender();
    },

    render: function() {
      
      /* Getting route properties */
      var routes = this.getRoutes();
      this.currentRoute = routes[routes.length - 1];
      var url = this.currentRoute.path.replace('${relative_url}', '');
      this.route_params = this.getParams();

      var routeName = this.currentRoute.name.replace('_with_paging', '');
      this.extra_properties = routesExtraProperties[routeName] || {};

      /* Identifying URL parts */
      var urlParts = url.split('/');
      this.controller = this.extra_properties.controller || (urlParts.length && urlParts[0] ? urlParts[0] : 'index').toLowerCase();
      this.action = this.extra_properties.action || (urlParts.length > 1 ? urlParts[1] : 'index').toLowerCase();
      
      if(Views[this.controller] && Views[this.controller][this.action]) {
        return (
          React.createElement(Views[this.controller][this.action].Main, {
            route_params: this.route_params,
            current_route: this.currentRoute,
            current_url: url
          })
        )
      } else {
        return (
          <div className="alert alert-danger">404 - File not found</div>
        )
      }
    }
  })
});

/* Initialization and routing */
var routingInit = function initialization() {
  Helpers.UI.DOM.GetBody().on('new_routing', function onNewRouting(event, data) {

    var bodyRef = Helpers.UI.DOM.GetBody();

    /* Setting Body Class (after removing the previous) */
    var previousClassName = bodyRef.data('viewClass');
    if(previousClassName) {
      bodyRef.removeClass(previousClassName);
    }
    var newClassName = 'view_' + data.controller + '_' + data.action;
    bodyRef.addClass(newClassName);
    bodyRef.data('viewClass', newClassName);

    /* route properties */
    if(data.extra_properties.fullWidth) {
      Helpers.UI.DOM.GetMainContent().removeClass('container');
    } else {
      Helpers.UI.DOM.GetMainContent().addClass('container');
    }
  });
};