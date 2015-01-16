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
        properties: this.route_properties
      });
    },

    componentDidUpdate: function() {
      this.onDOMRender();
    },

    componentDidMount: function() {
      this.onDOMRender();
    },

    render: function() {
      
      /* Getting URL */
      var url = this.getParams().splat || '';

      /* Getting current routes */
      var routes = this.getRoutes();
      var currentRoute = routes[routes.length - 1];

      /* Getting route properties */
      this.route_properties = routesExtraProperties[currentRoute.name] || {};

      /* Cleaning URL from hash parameters and querystring */
      var cutQuery = url.indexOf('?');
      if(cutQuery > -1) {
        url = url.substring(0, cutQuery);
      }

      var cutHash = url.indexOf('#');
      if(cutHash > -1) {
        url = url.substring(0, cutHash);
      }

      /* Identifying URL parts */
      var urlParts = url.split('/');
      this.controller = (urlParts.length && urlParts[0] ? urlParts[0] : 'index').toLowerCase();
      this.action = (urlParts.length > 1 ? urlParts[1] : 'index').toLowerCase();
      
      if(Views[this.controller] && Views[this.controller][this.action]) {
        return (
          React.createElement(Views[this.controller][this.action].Main, null)
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
    if(data.properties.fullWidth) {
      Helpers.UI.DOM.GetMainContent().removeClass('container');
    } else {
      Helpers.UI.DOM.GetMainContent().addClass('container');
    }
  });
};