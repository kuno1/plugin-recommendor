/**
 * List plugins component.
 */

/* global RecommenderList: false */

const { render, Component } = wp.element;
const { __, sprintf } = wp.i18n;

class Plugin extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			loading: true,
			title: '',
			url: '',
			install: '',
			description: '',
			notes: '',
			activated: false,
			src: '',
			error: false,
			priority: 0,
		};
	}

	componentDidMount() {
		wp.apiFetch( {
			path: 'kunoichi/v1/plugins/recommendation/' + this.props.slug,
		} ).then( ( res ) => {
			this.setState( res );
		} ).catch( ( res ) => {
			this.setState( {
				title: this.props.slug,
				error: true,
				description: res.message,
			} );
		} ).finally( () => {
			this.setState( {
				loading: false,
			} );
		} );
	}

	render() {
		const { loading, activated, error, url, install, priority } = this.state;
		const classNames = [ 'plugins-recommender-list__item' ];
		if ( this.state.loading ) {
			classNames.push( 'loading' );
		}
		if ( activated ) {
			classNames.push( 'plugins-recommender-list__activated' );
		}
		const reasonClass = [ 'plugins-recommender-list__note' ];
		let notes = '';
		if ( ! loading && ! this.state.notes ) {
			reasonClass.push( 'plugins-recommender-list__note--empty' );
			notes = __( 'No reason provided.', 'pr' );
		} else if ( ! loading ) {
			notes = this.state.notes;
		}
		let desc = '';
		if ( ! loading ) {
			desc = this.state.description.slice( 0, 200 ) + '...';
		}
		const installButtonClass = [ 'button-primary' ];
		if ( activated ) {
			installButtonClass.push( 'disabled' );
		}
		let priorityLabel = '';
		const priorityClass = [ 'plugins-recommender-list__subtitle' ];
		if ( 90 <= priority ) {
			priorityLabel = __( 'Required', 'pr' );
			priorityClass.push( 'plugins-recommender-list__subtitle--required' );
		} else if ( 50 <= priority ) {
			priorityLabel = __( 'Integrated', 'pr' );
			priorityClass.push( 'plugins-recommender-list__subtitle--integrated' );
		} else {
			priorityLabel = __( 'Recommended', 'pr' );
		}
		return (
			<div className={ classNames.join( ' ' ) }>
				<div className="plugins-recommender-list__inner">
					{ ( this.state.loading || this.state.error ) ? (
						<div className="plugins-recommender-list__placeholder">&nbsp;</div>
					) : (
						<img src={ this.state.src } alt="" className="plugins-recommender-list__img" />
					) }
					<div className="plugins-recommender-list__body">
						<h3 className="plugins-recommender-list__title">{ this.state.title }</h3>
						<p className="plugins-recommender-list__desc">{ desc }</p>
						<h4 className={ priorityClass.join( ' ' ) }>
							{ loading ? '' : priorityLabel }
						</h4>
						<p className={ reasonClass.join( ' ' ) }>{ notes }</p>
						{ ! ( error || loading ) ? (
							<p className="plugins-recommender-list__actions">
								<a href={ install } className={ installButtonClass.join( ' ' ) }>{ activated ? __( 'Activated', 'pr' ) : __( 'Install', 'pr' ) }</a>
								<a href={ url } rel="noopener noreferrer" target="_blank" className="button">{ __( 'Detail', 'pr' ) }</a>
							</p>
						) : null }
					</div>
				</div>
			</div>
		);
	}
}

class PluginList extends Component {

	constructor( props ) {
		super( props );
		this.plugins = RecommenderList.plugins;
	}

	render() {
		const plugins = this.plugins;
		return (
			plugins.length ? (
				<div className="plugins-recommender-list">
					<p className="description plugins-recommender-list__desc">{ sprintf( __( 'Recommended Plugins: %d', 'plugins-recommender' ), plugins.length ) }</p>
					<div className="plugins-recommender-list__grid">
						{ plugins.map( ( plugin ) => {
							return <Plugin key={ plugin } slug={ plugin } />;
						} ) }
					</div>
				</div>
			) : (
				<div className="notice notice-error plugins-recommender-list__error">
					{ __( 'No plugin is recommended.', 'plugins-recommender' ) }
				</div>
			)
		);
	}
}

render( <PluginList/>, document.getElementById( 'plugin-recommender-list' ) );
