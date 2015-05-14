'use strict';

GitLog.ChangeLog = React.createClass({
    getInitialState: function() {
        return {
            data: []
        };
    },
    componentWillReceiveProps: function(props) {
        this.setState({data: []});

        $.ajax({
            url: '/repos/' + props.owner + '/' + props.repo + '/tags/' + props.gitTag.name + '/commits',
            dataType: 'json',
            cache: false,
            success: function(response) {
                this.setState({data: response.commits});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
        });
    },
    render: function () {
        var Panel = ReactBootstrap.Panel;
        var ListGroup = ReactBootstrap.ListGroup;
        var title = 'Commits';

        if (jQuery.isPlainObject(this.props.gitTag)) {
            title = 'Commits for Tag ' + this.props.gitTag.name;
        }

        var nodes = this.state.data.map(function (data) {
            var ListGroupItem = ReactBootstrap.ListGroupItem;
            var sha = data.sha;
            return (
                <ListGroupItem key={sha} id={sha}>{data.message}</ListGroupItem>
            );
        });
        return (
            <Panel header={title}>
                <ListGroup className="changeLog" fill>
                    {nodes}
                </ListGroup>
            </Panel>
        );
    }
});
