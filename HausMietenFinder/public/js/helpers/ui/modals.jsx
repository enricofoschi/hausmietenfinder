(function(Current) {
    "use strict";

    var currentModals = [];

    var modalsContainer = $('<div></div>');
    Helpers.UI.DOM.GetBody().append(modalsContainer);
 
    Current.Modal = React.createClass({
        render: function () {
          return (
            <div className="modal">
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            Title
                        </div>
                        <div className="modal-body">
                            <p>One fine body&hellip;</p>
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" className="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
          );
        }
    });

    Current.Open = function Open(properties) {
        Current.CloseCurrentModals();

       // var newNode = <div></div>;
        React.render(<Helpers.UI.Modals.Modal />, modalsContainer[0]);
 
        /*properties.backdrop = properties.backdrop || 'static';
        properties.keyboard = properties.keyboard || false;
 
        DevDes.UI.Loader.Show();
 
        var modalInstance = NGGlobal.$modal.open(properties);
        DevDes.UI.Modals.CurrentModals.push({
            open: true,
            modal: modalInstance,
            allowMultiple: properties.allowMultiple
        });
 
        modalInstance.opened.then(function () {
            DevDes.UI.Loader.Hide();
        });
 
        return modalInstance;*/
    };
 
    Current.CloseCurrentModals = function Close(properties) {
        $.each(currentModals, function () {
            
        });
    };

})(defineNamespace("Helpers.UI.Modals"));