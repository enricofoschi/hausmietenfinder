(function(Current) {
	"use strict";

	Current.Main = React.createClass({

		mixins: [ReactRouter.Navigation],

		getInitialState: function getInitialState() {
			return {
				distances: [],
				current_page: this.props.route_params.page || 1
			};
		},

		onPageChange: function newPage(newPage) {
			this.state.current_page = newPage; // do not need to call setState as a refresh is not necessary now (data not loaded yet)

			this.loadData();
		},

		loadData: function loadData() {

			var thisRef = this;

			var searchService = new Services.HausMietenFinder.Distance();

			searchService.load(this.props.route_params.search_id, this.state.current_page).then(function onSuccess(response) {
				
				/* Handling the case there is a refresh on a last page and there are no results 
				 * (e.g. they have just been deleted)
				 */
				if(response.distances.length === 0 && thisRef.state.current_page > 0) {
					thisRef.state.current_page = Math.ceil(response.total_count / 12);
					thisRef.loadData();
				} else {
					thisRef.setState({
						distances: response.distances,
						total: response.total_count,
						search: response.search
					});
					Helpers.UI.DOM.ScrollTo($(thisRef.getDOMNode()));
				}
			});
		},

		componentDidMount: function onMount() {
			this.loadData();
		},

		onChangeStatus: function onChangeStatus(e) {

			var thisRef = this;

			Helpers.UI.DOM.StopPropagation(e);

			var sourceElement = $(e.target);
			var distanceId = sourceElement.parents(".house-container:first").attr("data-distanceid");
			var removeItem = sourceElement.attr("data-remove") === "1";

			var searchService = new Services.HausMietenFinder.Distance();

			searchService.changeStatus(distanceId, removeItem).then(function onSuccess(response) {
				thisRef.loadData();
			});
		},

		reset: function() {
			this.state.current_page = 1;

			this.transitionTo('search', {
				search_id: this.props.route_params.search_id,
				page: 1
			});

			this.loadData();
		},

		refresh: function () {
			var searchService = new Services.HausMietenFinder.Search();
			searchService.search({
				search_id: this.props.route_params.search_id
			}).then(this.reset);
		},

		render: function () {
			var content;
			var thisRef = this;

			if(this.state.distances.length) {

				var distanceGrid = Helpers.Core.Arrays.FilterToMatrix(this.state.distances, 4);

				content = (
					<div className="houses-list">
						{$.map(distanceGrid, function onDistanceGrid(distanceRow, rowIndex) {
							return (
								<div className="row" key={rowIndex}>
									{$.map(distanceRow, function onLoop(distance, colIndex) {
										var house = distance.house;

										return (
											<div data-distanceid={distance._id.$id} className="house-container col-md-3 col-sm-2" key={colIndex}>
												<div className={'house-content ' + (distance.status == 1 ? 'shortlisted' : '')}>
													<i className="fa fa-check-circle checked text-success"></i>
													<div className="picture-container">
														<img src={house.picture_url.replace(/60x60/g, '200x150')} className="full-width" />
													</div>

													<div className="border-bottom background-f0">
														<div className="row font24">
															<div className="col-xs-6 text-center border-right">
																<div className="pfull5">&euro;{Helpers.Core.Strings.FormatMoney(house.warm_miete, 0)}</div>
															</div>
															<div className="col-xs-6 text-center">
																{distance.transit_time ? (
																	<div className="pfull5"><i className="fa fa-bus text-muted"></i> {distance.transit_time}
																	<span className="font12">mins</span>
																</div>
																	) : '/'}																
															</div>
														</div>
													</div>

													<div className="house-body wrappable">
														{house.title}
														<div className="top5 pbottom10">
															<span className={(house.exact_address ? '' : 'hide ') + 'label right5 label-success'}>Genaue Anschrift</span>
															<span className={'label label-primary right5 ' + (house.private_offer ? '' : 'hide')}>Von Privat</span>
														</div>

														<hr className="small" />

														<div className="row">
															<div className="col-xs-6 text-center">
																<a href={'http://www.immobilienscout24.de/expose/' + house.immobilien24_id} target="_blank">
																	<i className="fa fa-home"></i> Öffnen
																</a>
															</div>
															<div className="col-xs-6 text-center">
																<a href={'https://www.google.com/maps/dir/' + encodeURIComponent(house.address_str) + '/' + encodeURIComponent(thisRef.state.search.location)} target="_blank">
																	<i className="fa fa-map-marker"></i> Karte
																</a>
															</div>
														</div>
													</div>		

													<div className="border-top choices-container">
														<a type='button' className="choice choice-success" onClick={thisRef.onChangeStatus}>Merken</a>
														<a type='button' className="choice choice-danger" data-remove="1" onClick={thisRef.onChangeStatus}>Entfernen</a>
													</div>										
												</div>
											</div>	
										);
									})}
								</div>		
							);
						})}
						<div className="text-center">
							<Components.UI.Paging.Pager paging={{
								elements_per_page: 12,
								route: {
									name: 'search_with_paging',
									params: {
										search_id: this.props.route_params.search_id 
									}
								},
								onPageChange: this.onPageChange,
								total_elements: this.state.total,
								current_page: this.state.current_page
							}} />
						</div>
					</div>					
				);
				
			} else {
				content = <div><i className="fa fa-gear fa-spin"></i> Warten Sie mal</div>
			}

			var searchUrl = '${absolute_url}search/' + this.props.route_params.search_id;

			return (
				<div>
					<div className="wrappable font18 bottom15 text-center background-ff pfull10">
						Ihre einzigartige Such URL: <a href={searchUrl}>{searchUrl}</a> 
						<a className="left10 text-success" onClick={this.refresh}><i className="fa fa-refresh"></i></a>
					</div>
					{content}
				</div>
			);
	     }
    });
})(defineNamespace("Views.houses.search"));