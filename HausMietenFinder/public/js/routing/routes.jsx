window.Route = ReactRouter.Route;
window.DefaultRoute = ReactRouter.DefaultRoute;
window.Link = ReactRouter.Link;
window.RouteHandler = ReactRouter.RouteHandler;

var routes = (
  <Route name="app" path="${relative_url}" handler={Components.App.Body}>
    <Route name="home" path="${relative_url}" handler={Components.App.MainRouteHandler} />
    <Route name="search" path="${relative_url}search/:search_id" handler={Components.App.MainRouteHandler} />
    <Route name="search_with_paging" path="${relative_url}search/:search_id/:page" handler={Components.App.MainRouteHandler} />
    <Route name="default" path="${relative_url}*" handler={Components.App.MainRouteHandler} />	    
  </Route>
);

var routesExtraProperties = {
	"home": {
		fullWidth: true
	},
	"search": {
		controller: 'houses',
		action: 'search'
	}
};