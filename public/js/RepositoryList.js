'use strict';

GitLog.RepositoryList = React.createClass({
    getInitialState: function() {
        return {data: []};
    },
    load: function() {
        $.ajax({
            url: '/users/' + this.props.owner + '/repos',
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    componentDidMount: function() {
        this.load();
    },
    render: function() {
        var me = this;
        var ListGroup = ReactBootstrap.ListGroup;
        var nodes = this.state.data.map(function (data) {
            var ListGroupItem = ReactBootstrap.ListGroupItem;
            var link = '#/Repos/' + me.props.owner + '/' + data.name;
            return (
                //<RepositoryListEntry key={data.id} owner={me.props.owner} repo={data.name}/>
                <ListGroupItem header={data.name} href={link}>{data.description}</ListGroupItem>
            );
        });
        return (
            <ListGroup className="repositoryList">
                {nodes}
            </ListGroup>
        );
    }
});
