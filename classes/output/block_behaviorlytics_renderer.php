<?php

class block_behaviorlytics_renderer extends plugin_renderer_base {
	/**
	 * Renders the HTML for the index page.
	 * @param array $headings Headings for the display columns.
	 * @param array $align Alignment for each column.
	 * @param array $data All of the table data.
	 * @return string
	 */
	public function render_index($headings, $align, $data) {

		return \html_writer::table($table);
	}
}
