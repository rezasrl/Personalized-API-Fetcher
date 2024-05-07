<?php
namespace Personalized_Api_Fetcher\Widgets;

use Personalized_Api_Fetcher\Main as Main;
use WP_Widget;

class PAF_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'paf_widget',
			esc_html__( 'Personalized API Fetcher Widget', 'personalized-api-fetcher' ),
			[
				'description' => esc_html__( 'Displays data fetched from the external API.', 'personalized-api-fetcher' ),
			]
		);
	}

	public static function register() {
		register_widget( __CLASS__ );
	}

	public function form( $instance ) {
		?>
			<p><?php _e( 'This widget displays data fetched from the external API.', 'personalized-api-fetcher' ); ?></p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	public function widget( $args, $instance ) {
		if ( ! is_user_logged_in() ) {
			return;
		}

		echo $args['before_widget'];
		echo $args['before_title'] . esc_html__( 'Personalized API Fetcher Widget', 'personalized-api-fetcher' ) . $args['after_title'];

		$api_data = Main::api_fetch_data();
		?>

		<ul>
			<?php if ( ! empty( $api_data ) ) : ?>
				<?php foreach( $api_data as $key => $value ) : ?>
					<li><?php echo esc_html( $key . ' : ' . $value ); ?></li>
				<?php endforeach; ?>
			<?php else : ?>
				<li><?php echo esc_html__( 'No data available.', 'personalized-api-fetcher' ); ?></li>
			<?php endif; ?>
		</ul>

		<?php
		echo $args['after_widget'];
	}
}
