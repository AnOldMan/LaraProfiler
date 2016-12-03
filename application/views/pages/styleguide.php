<?php Asset::style( 'styleguide-css', 'styleguide.css' ) ?>
				<div class="padding-top">
					<h1>Components</h1>

					<section>
						<h2>Branding Elements</h2>
						<p>These are the default colors and typefaces in this boilerplate.</p>
						<h3>Colors</h3>
						<div class="clearfix">
							<div class="float-left branding-color-box" style="background-color: #0088cc; color: white;" title="$color-primary">#0088cc</div>
							<div class="float-left branding-color-box" style="background-color: #0000bb; color: white;" title="$color-blue">#0000bb</div>
							<div class="float-left branding-color-box" style="background-color: #b3d4fc; color: white;" title="$color-lightblue">#b3d4fc</div>
							<div class="float-left branding-color-box" style="background-color: #990055; color: white;" title="$color-purp">#990055</div>
							<div class="float-left branding-color-box" style="background-color: #dd4a68; color: white;" title="$color-pink">#dd4a68</div>
							<div class="float-left branding-color-box" style="background-color: #dd0000; color: white;" title="$color-red">#dd0000</div>
							<div class="float-left branding-color-box" style="background-color: #007700; color: white;" title="$color-green">#007700</div>
							<div class="float-left branding-color-box" style="background-color: #8b0000; color: white;" title="$color-burg">#8b0000</div>
							<div class="float-left branding-color-box" style="background-color: #ee9900; color: white;" title="$color-gold">#ee9900</div>
							<div class="float-left branding-color-box" style="background-color: #ffff00;" title="$color-yellow">#ffff00</div>
							<div class="float-left branding-color-box" style="background-color: #a67f59; color: white;" title="$color-brown">#a67f59</div>
						</div>
						<h3>Grays</h3>
						<div class="clearfix">
							<div class="float-left branding-color-box" style="background-color: #272727; color: white;" title="$color-black">#272727</div>
							<div class="float-left branding-color-box" style="background-color: #666666; color: white;" title="$color-gray-darker">#666666</div>
							<div class="float-left branding-color-box" style="background-color: #808080; color: white;" title="$color-gray-dark">#808080</div>
							<div class="float-left branding-color-box" style="background-color: #e5e5e5;" title="$color-gray-light">#e5e5e5</div>
							<div class="float-left branding-color-box" style="background-color: #fcfcff;" title="$color-extra-light">#fcfcff</div>
							<div class="float-left branding-color-box" style="background-color: #ffffff;" title="$color-white">#ffffff</div>
						</div>

						<h3>Font Stacks</h3>
						<p class="branding-font" style="font-family: 'Helvetica Neue', Arial, sans-serif">Primary: "Helvetica Neue", Arial, sans-serif</p>
						<p class="branding-font" style="font-family: Georgia, Times, serif">Secondary: Georgia, Times, serif;</p>
						<p class="branding-font" style="font-family: Menlo, Monaco, 'Courier New',-Monospace;">Monospace: Menlo, Monaco, "Courier New",-Monospace</p>
						<p class="branding-font" style="font-family: Arial, 'Liberation Sans', FreeSans, sans-serif;">Form: Arial, "Liberation Sans", FreeSans, sans-serif</p>
						<p class="branding-font" style="font-family: 'Lucida Grande', Arial, 'Liberation Sans', FreeSans, sans-serif;">Form-message: "Lucida Grande", Arial, "Liberation Sans", FreeSans, sans-serif</p>
						<hr/>

						<h3>Breakpoints</h3>
						<ul class="breakpoints">
							<li style="width: 20em">$bp-xsmall: 20em;</li>
							<li style="width: 30em">$bp-small: 30em;</li>
							<li style="width: 40em">$bp-medium: 40em;</li>
							<li style="width: 60em">$bp-large: 60em;</li>
							<li style="width: 80em">$bp-xlarge: 80em;</li>
						</ul>
						<h3>Responsive</h3>
						<ul class="breakpoints">
							<li style="width: 320px">$mobile_min: 320px;</li>
							<li style="width: 767px">$tablet_min: 767px;</li>
							<li style="width: 960px">$wide_min: 960px;</li>
						</ul>
					</section>
					<hr/>

					<section>
						<h2>The Grid</h2>
						<p>This boilerplate uses a fluid, mobile-first grid system based on simple fractions&mdash;halves, thirds, and fourths. It supports nesting, and includes special classes for offsets, content choreography, and dynamic grids.</p>
						<h3>The Base Grid</h3>
						<p>The <code>.container</code> class centers content on the page and restricts it to a maximum width. To create a grid, add a <code>&lt;div&gt;</code> with a <code>.row</code> class. You can create grids within a row by creating <code>&lt;div&gt;</code> elements with the <code>.grid-$size</code> class.</p>
						<div class="row">
							<div class="grid-third"><div class="grid-highlight">.grid-third</div></div>
							<div class="grid-two-thirds"><div class="grid-highlight">.grid-two-thirds</div></div>
						</div>

						<div class="row">
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
							<div class="grid-three-fourths"><div class="grid-highlight">.grid-three-fourths</div></div>
						</div>

						<div class="row">
							<div class="grid-half"><div class="grid-highlight">.grid-half</div></div>
							<div class="grid-half"><div class="grid-highlight">.grid-half</div></div>
						</div>

						<div class="row">
							<div class="grid-full"><div class="grid-highlight">.grid-full</div></div>
						</div>

						<pre><code class="lang-markup">&lt;div class=&quot;container&quot;&gt;

	&lt;p&gt;This boilerplate uses a fluid, mobile-first grid system...&lt;/p&gt;

	&lt;div class=&quot;row&quot;&gt;
		&lt;div class=&quot;grid-third&quot;&gt;.grid-third&lt;/div&gt;
		&lt;div class=&quot;grid-two-thirds&quot;&gt;.grid-two-thirds&lt;/div&gt;
	&lt;/div&gt;

	&lt;div class=&quot;row&quot;&gt;
		&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
		&lt;div class=&quot;grid-three-fourths&quot;&gt;.grid-three-fourths&lt;/div&gt;
	&lt;/div&gt;

	&lt;div class=&quot;row&quot;&gt;
		&lt;div class=&quot;grid-half&quot;&gt;.grid-half&lt;/div&gt;
		&lt;div class=&quot;grid-half&quot;&gt;.grid-half&lt;/div&gt;
	&lt;/div&gt;

	&lt;div class=&quot;row&quot;&gt;
		&lt;div class=&quot;grid-full&quot;&gt;.grid-full&lt;/div&gt;
	&lt;/div&gt;

&lt;/div&gt;</code></pre>
						<h3>Offsets</h3>
						<p>Push grids to the right by adding an <code>.offset-$size</code> class. Center grids with the <code>.float-center</code> class.</p>
						<div class="row">
							<div class="grid-three-fourths offset-fourth"><div class="grid-highlight">.grid-three-fourths .offset-fourth</div></div>
						</div>

						<div class="row">
							<div class="grid-third"><div class="grid-highlight">.grid-third</div></div>
							<div class="grid-third offset-third"><div class="grid-highlight">.grid-third .offset-third</div></div>
						</div>

						<div class="row">
							<div class="grid-two-thirds float-center"><div class="grid-highlight">.grid-two-thirds .float-center</div></div>
						</div>

						<pre><code class="lang-markup">&lt;div class=&quot;row&quot;&gt;
	&lt;div class=&quot;grid-three-fourths offset-fourth&quot;&gt;.grid-three-fourths .offset-fourth&lt;/div&gt;
&lt;/div&gt;

&lt;div class=&quot;row&quot;&gt;
	&lt;div class=&quot;grid-third&quot;&gt;.grid-third&lt;/div&gt;
	&lt;div class=&quot;grid-third offset-third&quot;&gt;.grid-third .offset-third&lt;/div&gt;
&lt;/div&gt;

&lt;div class=&quot;row&quot;&gt;
	&lt;div class=&quot;grid-two-thirds float-center&quot;&gt;.grid-two-thirds .float-center&lt;/div&gt;
&lt;/div&gt;</code></pre>
						<h3>Grids on Small Screens</h3>
						<p>The grid activates on medium-sized screens by default. You can toggle the grid on smaller screens by adding a simple class-<code>.row-start-xsmall</code> or <code>.row-start-small</code>-to the desired <code>.row</code> element.</p>
						<p>If you&#39;re <a href="setup.html#working-with-the-source-files">working with the source files</a>, you can also easily customize when the grid activates by default and adjust or add additional <code>.row-start-$size</code> classes.</p>
						<p><strong>Extra Small Screens</strong></p>
						<div class="row row-start-xsmall">
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
						</div>

						<p><strong>Small Screens</strong></p>
						<div class="row row-start-small">
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
							<div class="grid-fourth"><div class="grid-highlight">.grid-fourth</div></div>
						</div>

						<pre><code class="lang-markup">Extra Small Screens
&lt;div class=&quot;row row-start-xsmall&quot;&gt;
	&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
	&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
	&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
	&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
&lt;/div&gt;

Small Screens
&lt;div class=&quot;row row-start-small&quot;&gt;
	&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
	&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
	&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
	&lt;div class=&quot;grid-fourth&quot;&gt;.grid-fourth&lt;/div&gt;
&lt;/div&gt;</code></pre>
						<h3>Content Choreography</h3>
						<p>Flip the order of a grid on bigger screens by adding the <code>.grid-flip</code> class.</p>
						<div class="row">
							<div class="grid-third grid-flip"><div class="grid-highlight">First in HTML</div></div>
							<div class="grid-two-thirds"><div class="grid-highlight">Second in HTML</div></div>
						</div>

						<pre><code class="lang-markup">&lt;div class=&quot;row&quot;&gt;
	&lt;div class=&quot;grid-third grid-flip&quot;&gt;First in HTML&lt;/div&gt;
	&lt;div class=&quot;grid-two-thirds&quot;&gt;Second in HTML&lt;/div&gt;
&lt;/div&gt;</code></pre>
						<h3>Dynamic Grids</h3>
						<p>Create grids that vary in size based on screen width using the <code>.grid-dynamic</code> class&mdash;great for creating images galleries! For content that may vary in height, you may want to use the <a href="https://github.com/cferdinandi/right-height/">Right Height add-on</a>.</p>
						<p>If you&#39;re <a href="setup.html#working-with-the-source-files">working with the source files</a>, you easily adjust the grid breakpoints and even create additional grids with different layout patterns.</p>
						<div class="row">
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
							<div class="grid-dynamic"><p><img src="/assets/images/350x350.png" title="Picture an emu"></p></div>
						</div>

						<pre><code class="lang-markup">&lt;div class=&quot;row&quot;&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
	&lt;div class=&quot;grid-dynamic&quot;&gt;&lt;img src=&quot;emu.jpg&quot;&gt;&lt;/div&gt;
&lt;/div&gt;</code></pre>
					</section>
					<hr/>

					<section>
						<h2>Typography</h2>
						<p>This boilerplate uses relative sizing (ems and %, not pixels) for font sizing.</p>
						<p>The <code>body</code> has a base <code>font-size</code> of 100%, which is 16px on browsers with default font settings. All other sizes are in ems. Changing the <code>font-size</code> on the <code>body</code> element will adjust the typographical scale for the entire site.</p>

						<p><em>New to relative sizing? <a href="http://gomakethings.com/working-with-relative-sizing/">Learn more.</a></em></p>
						<h3>Text Basics</h3>
						<p>Default text<br>
						<span class="text-muted">Muted text</span><br>
						<span class="text-small">Small text</span><br>
						<span class="text-large">Large text</span><br>
						<a href="#">Hyperlinks</a><br>
						<strong>Bold</strong> and <em>italics</em></p>
						<pre><code class="lang-markup">Default text
&lt;span class=&quot;text-muted&quot;&gt;Muted text&lt;/span&gt;
&lt;span class=&quot;text-small&quot;&gt;Small text&lt;/span&gt;
&lt;span class=&quot;text-large&quot;&gt;Large text&lt;/span&gt;
&lt;a href=&quot;#&quot;&gt;Hyperlinks&lt;/a&gt;
&lt;strong&gt;Bold&lt;/strong&gt; and &lt;em&gt;italics&lt;/em&gt;</code></pre>
						<p><em>Because this boilerplate uses relative sizing, you should always apply <code>.text-tall</code> and <code>.text-small</code> classes to a <code>&lt;span&gt;</code> element and not directly to a <code>&lt;p&gt;</code>. Otherwise, your spacing will get all messed up. The `<code>.text-xx</code>` classes are in `<code>_overrides.scss</code>` for better cascade inheritance.</em></p>

						<h3>Available WebFonts</h3>
						<div class="webfont">
							<ul>
								<li class="roboto-cond">Roboto Condensed</li>
								<li class="roboto-cond-bold">Roboto Condensed Bold</li>
								<li class="roboto">Roboto</li>
								<li class="roboto-bold">Roboto Bold</li>
								<li class="roboto-black">Roboto Black</li>
							</ul>
							<ul>
								<li class="opensans-light">OpenSans Light</li>
								<li class="opensans-light-italic">OpenSans Light Italic</li>
								<li class="opensans">OpenSans</li>
								<li class="opensans-italic">OpenSans Italic</li>
								<li class="opensans-semi-bold">OpenSans Semi-Bold</li>
								<li class="opensans-semi-bold-italic">OpenSans Semi-Bold Italic</li>
								<li class="opensans-bold">OpenSans Bold</li>
								<li class="opensans-bold-italic">OpenSans Bold Italic</li>
								<li class="opensans-extra-bold">OpenSans Extra-Bold</li>
								<li class="opensans-extra-bold-italic">OpenSans Extra-Bold Italic</li>
							</ul>
							<ul>
								<li class="ubuntu-light">Ubuntu Light</li>
								<li class="ubuntu-light-italic">Ubuntu Light Italic</li>
								<li class="ubuntu-light-bold">Ubuntu Light Bold</li>
								<li class="ubuntu-light-bold-italic">Ubuntu Light Bold Italic</li>
								<li class="ubuntu-cond">Ubuntu Condensed</li>
								<li class="ubuntu">Ubuntu</li>
								<li class="ubuntu-italic">Ubuntu Italic</li>
								<li class="ubuntu-bold">Ubuntu Bold</li>
								<li class="ubuntu-bold-italic">Ubuntu Bold Italic</li>
								<li class="ubuntu-mono">Ubuntu Mono</li>
								<li class="ubuntu-mono-italic">Ubuntu Mono Italic</li>
								<li class="ubuntu-mono-bold">Ubuntu Mono Bold</li>
								<li class="ubuntu-mono-bold-italic">Ubuntu Mono Bold Italic</li>
							</ul>
							<ul>
								<li class="dited">Dited</li>
							</ul>
						</div>
					</section>
					<hr/>

					<section>
						<h2>Lists</h2>
						<p>This boilerplate includes styling for ordered, unordered, unstyled, and definition lists.</p>
						<div class="row">
							<div class="grid-third">
								<strong>Ordered</strong>
								<ol>
									<li>Item 1</li>
									<li>Item 2</li>
									<li>Item 3</li>
								</ol>
							</div>
							<div class="grid-third">
								<strong>Unordered</strong>
								<ul>
									<li>Item 1</li>
									<li>Item 2</li>
									<li>Item 3</li>
								</ul>
							</div>
							<div class="grid-third">
								<strong>Unstyled</strong>
								<ul class="list-unstyled">
									<li>Item 1</li>
									<li>Item 2</li>
									<li>Item 3</li>
								</ul>
							</div>
						</div>

						<div>
							<strong>Inline</strong>
							<ul class="list-inline">
								<li>Item 1</li>
								<li>Item 2</li>
								<li>Item 3</li>
							</ul>
						</div>

						<dl>
							<dt>Definition List</dt>
							<dd>Encloses a list of pairs of terms and descriptions. Common uses for this element are to implement a glossary or to display metadata (a list of key-value pairs).</dd>

							<dt>Here&#39;s another term</dt>
							<dd>And here&#39;s the definition that would go with it.</dd>
						</dl>

						<pre><code class="lang-markup">Ordered
&lt;ol&gt;
	&lt;li&gt;Item 1&lt;/li&gt;
	&lt;li&gt;Item 2&lt;/li&gt;
	&lt;li&gt;Item 3&lt;/li&gt;
&lt;/ol&gt;

Unordered
&lt;ul&gt;
	&lt;li&gt;Item 1&lt;/li&gt;
	&lt;li&gt;Item 2&lt;/li&gt;
	&lt;li&gt;Item 3&lt;/li&gt;
&lt;/ul&gt;

Unstyled
&lt;ul class=&quot;list-unstyled&quot;&gt;
	&lt;li&gt;Item 1&lt;/li&gt;
	&lt;li&gt;Item 2&lt;/li&gt;
	&lt;li&gt;Item 3&lt;/li&gt;
&lt;/ul&gt;

Inline
&lt;ul class=&quot;list-inline&quot;&gt;
	&lt;li&gt;Item 1&lt;/li&gt;
	&lt;li&gt;Item 2&lt;/li&gt;
	&lt;li&gt;Item 3&lt;/li&gt;
&lt;/ul&gt;

&lt;dl&gt;
	&lt;dt&gt;Definition List&lt;/dt&gt;
	&lt;dd&gt;Encloses a list of pairs of terms and descriptions. Common uses for this element are to implement a glossary or to display metadata (a list of key-value pairs).&lt;/dd&gt;

	&lt;dt&gt;Here&#39;s another term&lt;/dt&gt;
	&lt;dd&gt;And here&#39;s the definition that would go with it.&lt;/dd&gt;
&lt;/dl&gt;</code></pre>
						<p><em>For semantic reasons, <code>.list-unstyled</code> and <code>.list-inline</code> should only be applied to unordered lists.</em></p>
					</section>
					<hr/>

					<section>
						<h2>Headings</h2>
						<p>This boilerplate includes styling for <code>h1</code> through <code>h6</code> headings.</p>
						<h1>h1. Heading 1</h1>
						<h2>h2. Heading 2</h2>
						<h3>h3. Heading 3</h3>
						<h4>h4. Heading 4</h4>
						<h5>h5. Heading 5</h5>
						<h6>h6. Heading 6</h6>
						<pre><code class="lang-markup">&lt;h1&gt;h1. Heading 1&lt;/h1&gt;
&lt;h2&gt;h2. Heading 2&lt;/h2&gt;
&lt;h3&gt;h3. Heading 3&lt;/h3&gt;
&lt;h4&gt;h4. Heading 4&lt;/h4&gt;
&lt;h5&gt;h5. Heading 5&lt;/h5&gt;
&lt;h6&gt;h6. Heading 6&lt;/h6&gt;</code></pre>
						<h3>Semantic Heading Classes</h3>
						<p>All heading values can also be applied as classes. For example, if a heading should be an <code>h1</code> element semantically, but you would like it to be styled as an <code>h4</code> element, you can apply <code>class=&quot;h4&quot;</code> to the <code>h1</code> element.</p>
						<h1 class="h4 text-left">This is an h1 heading that&#39;s styled as an h4 heading</h1>

						<pre><code class="lang-markup">&lt;h1 class=&quot;h4 text-left&quot;&gt;This is an h1 heading that&#39;s styled as an h4 heading&lt;/h1&gt;</code></pre>
					</section>
					<hr/>

					<section>
						<h2>Quotes and Citations</h2>
						<blockquote>
						<p>Someone once said something so important, it was deemed worthy of repeating here in the form of a blockquote. This is that blockquote.
						<cite>- Someone Important</cite></p>
						</blockquote>
						<p>You can also include superscripts<sup>1</sup> and subscripts<sub>xyz</sub>.</p>
						<pre><code class="lang-markup">&lt;blockquote&gt;
	Someone once said something so important, it was deemed worthy of repeating here in the form of a blockquote. This is that blockquote.
	&lt;cite&gt;- Someone Important&lt;/cite&gt;
&lt;/blockquote&gt;

You can also include superscripts&lt;sup&gt;1&lt;/sup&gt; and subscripts&lt;sub&gt;xyz&lt;/sub&gt;.</code></pre>
					</section>
					<hr/>

					<section>
						<h2>Code</h2>
						<p>Inline code: <code>.js-example</code>.</p>
						<pre><code class="language-css">/* Preformatted Text */
.js-example {
	color: #272727;
	background: #ffffff;
}</code></pre>
						<pre><code class="lang-markup">&lt;p&gt;Inline code: &lt;code&gt;.js-example&lt;/code&gt;&lt;/p&gt;

&lt;pre&gt;
	&lt;code&gt;
		/* Preformatted Text */
		.js-example {
			color: #272727;
			background: #ffffff;
		}
	&lt;/code&gt;
&lt;/pre&gt;</code></pre>
						<p>You&#39;ll need to escape brackets contained in code by typing <code>&amp;lt;</code> for <code>&lt;</code> and <code>&amp;gt;</code> for <code>&gt;</code>. The syntax highlighting used in this documentation is provided by <a href="http://prismjs.com/">Prism</a> by Lea Verou.</p>
					</section>
					<hr/>

					<section>
						<h2>Lines</h2>
						<p>Add lines to your markup using the <code>&lt;hr&gt;</code> element.</p>
					</section>
					<hr/>

					<section>
						<h2>Buttons</h2>
						<p>Button styles can be applied to anything with the <code>.btn</code> class applied. However, typically you&#39;ll want to apply these to only <code>&lt;a&gt;</code>, <code>&lt;button&gt;</code>, and <code>&lt;input type=&quot;submit&quot; /&gt;</code> elements for the best rendering.</p>
						<h3>Basic Buttons</h3>
						<button class="btn">Button</button>
						<button class="btn btn-secondary">Button Secondary</button>
						<button class="btn btn-large">Button Large</button>

						<button class="btn btn-block">Button Block</button>
						<button class="btn btn-secondary btn-block">Button Block</button>

						<pre><code class="lang-markup">&lt;button class=&quot;btn&quot;&gt;Button&lt;/button&gt;
&lt;button class=&quot;btn btn-secondary&quot;&gt;Button Secondary&lt;/button&gt;
&lt;button class=&quot;btn btn-large&quot;&gt;Button Large&lt;/button&gt;

&lt;button class=&quot;btn btn-block&quot;&gt;Button Block&lt;/button&gt;
&lt;button class=&quot;btn btn-secondary btn-block&quot;&gt;Button Block&lt;/button&gt;</code></pre>
						<h3>Active and Disabled Buttons</h3>
						<p>Use <code>.active</code> and <code>.disabled</code> classes to change the appearance of buttons&mdash;useful for creating apps.</p>
						<button class="btn active">Active</button>
						<button class="btn btn-secondary active">Secondary Active</button>
						<button class="btn disabled">Disabled</button>
						<button class="btn btn-secondary disabled">Secondary Disabled</button>

						<pre><code class="lang-markup">&lt;button class=&quot;btn active&quot;&gt;Active&lt;/button&gt;
&lt;button class=&quot;btn btn-secondary active&quot;&gt;Secondary Active&lt;/button&gt;
&lt;button class=&quot;btn disabled&quot;&gt;Disabled&lt;/button&gt;
&lt;button class=&quot;btn btn-secondary disabled&quot;&gt;Secondary Disabled&lt;/button&gt;</code></pre>
					</section>
					<hr/>

					<section>
						<h2>Tables</h2>
						<p>This boilerplate includes simple, easy-to-read table styling.</p>
						<table>
							<thead>
								<tr>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Super Hero</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Peter</td>
									<td>Parker</td>
									<td>Spiderman</td>
								</tr>
								<tr>
									<td>Bruce</td>
									<td>Wayne</td>
									<td>Batman</td>
								</tr>
								<tr>
									<td>Clark</td>
									<td>Kent</td>
									<td>Superman</td>
								</tr>
							</tbody>
						</table>

						<pre><code class="lang-markup">&lt;table&gt;
	&lt;thead&gt;
		&lt;tr&gt;
			&lt;th&gt;First Name&lt;/th&gt;
			&lt;th&gt;Last Name&lt;/th&gt;
			&lt;th&gt;Super Hero&lt;/th&gt;
		&lt;/tr&gt;
	&lt;/thead&gt;
	&lt;tbody&gt;
		&lt;tr&gt;
			&lt;td&gt;Peter&lt;/td&gt;
			&lt;td&gt;Parker&lt;/td&gt;
			&lt;td&gt;Spiderman&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;Bruce&lt;/td&gt;
			&lt;td&gt;Wayne&lt;/td&gt;
			&lt;td&gt;Batman&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;Clark&lt;/td&gt;
			&lt;td&gt;Kent&lt;/td&gt;
			&lt;td&gt;Superman&lt;/td&gt;
		&lt;/tr&gt;
	&lt;/tbody&gt;
&lt;/table&gt;</code></pre>
						<h3>Condensed Table</h3>
						<p>Add the <code>.table-condensed</code> class for more compact tables.</p>
						<table class="table-condensed">
							<thead>
								<tr>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Super Hero</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Peter</td>
									<td>Parker</td>
									<td>Spiderman</td>
								</tr>
								<tr>
									<td>Bruce</td>
									<td>Wayne</td>
									<td>Batman</td>
								</tr>
								<tr>
									<td>Clark</td>
									<td>Kent</td>
									<td>Superman</td>
								</tr>
							</tbody>
						</table>

						<pre><code class="lang-markup">&lt;table class=&quot;table-condensed&quot;&gt;
	...
&lt;/table&gt;</code></pre>
						<h3>Zebra Striping</h3>
						<p>Add zebra striping for easier readability with the <code>.table-striped</code> class.</p>
						<table class="table-striped">
							<thead>
								<tr>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Super Hero</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Peter</td>
									<td>Parker</td>
									<td>Spiderman</td>
								</tr>
								<tr>
									<td>Bruce</td>
									<td>Wayne</td>
									<td>Batman</td>
								</tr>
								<tr>
									<td>Clark</td>
									<td>Kent</td>
									<td>Superman</td>
								</tr>
							</tbody>
						</table>

						<pre><code class="lang-markup">&lt;table class=&quot;table-striped&quot;&gt;
	...
&lt;/table&gt;</code></pre>
						<h3>Responsive Table</h3>
						<p>Add the <code>.table-responsive</code> class for tables that reformat on smaller viewports. Use the <code>[data-label]</code> data attribute to apply the label that displays on smaller viewports.</p>
						<table class="table-responsive">
							<thead>
								<tr>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Super Hero</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td data-label="First Name">Peter</td>
									<td data-label="Last Name">Parker</td>
									<td data-label="Super Hero">Spiderman</td>
								</tr>
								<tr>
									<td data-label="First Name">Bruce</td>
									<td data-label="Last Name">Wayne</td>
									<td data-label="Super Hero">Batman</td>
								</tr>
								<tr>
									<td data-label="First Name">Clark</td>
									<td data-label="Last Name">Kent</td>
									<td data-label="Super Hero">Superman</td>
								</tr>
							</tbody>
						</table>

						<pre><code class="lang-markup">&lt;table class=&quot;table-responsive&quot;&gt;
	&lt;thead&gt;
		&lt;tr&gt;
			&lt;th&gt;First Name&lt;/th&gt;
			&lt;th&gt;Last Name&lt;/th&gt;
			&lt;th&gt;Super Hero&lt;/th&gt;
		&lt;/tr&gt;
	&lt;/thead&gt;
	&lt;tbody&gt;
		&lt;tr&gt;
			&lt;td data-label=&quot;First Name&quot;&gt;Peter&lt;/td&gt;
			&lt;td data-label=&quot;Last Name&quot;&gt;Parker&lt;/td&gt;
			&lt;td data-label=&quot;Super Hero&quot;&gt;Spiderman&lt;/td&gt;
		&lt;/tr&gt;
		...
	&lt;/tbody&gt;
&lt;/table&gt;</code></pre>
						<h3>Combining Classes</h3>
						<p>Classes can be combined as needed.</p>
						<table class="table-condensed table-striped table-responsive">
							<thead>
								<tr>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Super Hero</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td data-label="First Name">Peter</td>
									<td data-label="Last Name">Parker</td>
									<td data-label="Super Hero">Spiderman</td>
								</tr>
								<tr>
									<td data-label="First Name">Bruce</td>
									<td data-label="Last Name">Wayne</td>
									<td data-label="Super Hero">Batman</td>
								</tr>
								<tr>
									<td data-label="First Name">Clark</td>
									<td data-label="Last Name">Kent</td>
									<td data-label="Super Hero">Superman</td>
								</tr>
							</tbody>
						</table>

						<pre><code class="lang-markup">&lt;table class=&quot;table-condensed table-striped table-responsive&quot;&gt;
	...
&lt;/table&gt;</code></pre>
					</section>
					<hr/>

					<section>
						<h2>Forms</h2>
						<p>Labels, legends and inputs are styled as full width block elements (with the exception of checkboxes and radio buttons).</p>
						<h3>Basic Forms</h3>
						<form>
							<label>Label</label>
							<input type="text" value="input type=&quot;text&quot;" />
							<label>
								<input type="checkbox" />
								Option 1
							</label>
							<label>
								<input type="checkbox" />
								Option 2
							</label>
							<label>
								<input type="radio" name="radioset" />
								Option 1
							</label>
							<label>
								<input type="radio" name="radioset" />
								Option 2
							</label>
							<select>
								<option>Option 1</option>
								<option>Option 2</option>
								<option>Option 3</option>
							</select>
							<textarea>textarea</textarea>
						</form>

						<pre><code class="lang-markup">&lt;form&gt;
	&lt;label&gt;Label&lt;/label&gt;
	&lt;input type=&quot;text&quot; /&gt;
	&lt;label&gt;
		&lt;input type=&quot;checkbox&quot; /&gt;
		Option 1
	&lt;/label&gt;
	&lt;label&gt;
		&lt;input type=&quot;checkbox&quot; /&gt;
		Option 2
	&lt;/label&gt;
	&lt;label&gt;
		&lt;input type=&quot;radio&quot; name=&quot;radioset&quot; /&gt;
		Option 1
	&lt;/label&gt;
	&lt;label&gt;
		&lt;input type=&quot;radio&quot; name=&quot;radioset&quot; /&gt;
		Option 2
	&lt;/label&gt;
	&lt;select&gt;
		&lt;option&gt;Select 1&lt;/option&gt;
		&lt;option&gt;Select 2&lt;/option&gt;
		&lt;option&gt;Select 3&lt;/option&gt;
	&lt;/select&gt;
	&lt;textarea&gt;&lt;/textarea&gt;
&lt;/form&gt;</code></pre>
						<p><em>Wrap checkboxes and radio buttons inside a <code>&lt;label&gt;</code> to make them easier to click.</em></p>


						<h3>Form Layouts</h3>
						<p>Use the grid system to add structure to your forms.</p>
						<form>
							<div class="row">
								<div class="grid-fourth">
										<label>Name</label>
								</div>
								<div class="grid-three-fourths">
										<input type="text" required="required" />
								</div>
							</div>
							<div class="row">
								<div class="grid-fourth">
										<label>Message</label>
								</div>
								<div class="grid-three-fourths">
										<textarea></textarea>
								</div>
							</div>
							<div class="row">
								<div class="grid-three-fourths offset-fourth">
									<button class="btn btn-blue">Submit</button>
								</div>
							</div>
						</form>

						<pre><code class="lang-markup">&lt;form&gt;
	&lt;div class=&quot;row&quot;&gt;
		&lt;div class=&quot;grid-fourth&quot;&gt;
				&lt;label&gt;Name&lt;/label&gt;
		&lt;/div&gt;
		&lt;div class=&quot;grid-three-fourths&quot;&gt;
				&lt;input type=&quot;text&quot; /&gt;
		&lt;/div&gt;
	&lt;/div&gt;
	&lt;div class=&quot;row&quot;&gt;
		&lt;div class=&quot;grid-fourth&quot;&gt;
				&lt;label&gt;Message&lt;/label&gt;
		&lt;/div&gt;
		&lt;div class=&quot;grid-three-fourths&quot;&gt;
				&lt;textarea&gt;&lt;/textarea&gt;
		&lt;/div&gt;
	&lt;/div&gt;
	&lt;div class=&quot;row&quot;&gt;
		&lt;div class=&quot;grid-three-fourths offset-fourth&quot;&gt;
			&lt;button class=&quot;btn btn-blue&quot;&gt;Submit&lt;/button&gt;
		&lt;/div&gt;
	&lt;/div&gt;
&lt;/form&gt;</code></pre>
						<h3>Condensed &amp; Inline Inputs</h3>
						<p>For smaller forms, add the <code>.input-condensed</code> class to your input fields. For inline form elements, add the <code>.input-inline</code> class.</p>
						<form>
							<input type="text" class="input-inline" placeholder=".input-inline" />
							<input type="text" class="input-condensed input-inline" placeholder=".input-condensed" />
						</form>
						<pre><code class="lang-markup">&lt;form&gt;
	&lt;input type=&quot;text&quot; class=&quot;input-inline&quot; /&gt;
	&lt;input type=&quot;text&quot; class=&quot;input-condensed input-inline&quot; /&gt;
&lt;/form&gt;</code></pre>

						<h3>Fixed-size Inputs</h3>
						<form>
							<input type="text" class="input-inline input-tiny" placeholder="-tiny" />
							<input type="text" class="input-inline input-small" placeholder="-small" />
							<input type="text" class="input-inline input-medium" placeholder="-medium" />
							<input type="text" class="input-inline input-large" placeholder="-large" />
							<input type="text" class="input-inline input-xlarge" placeholder="-xlarge" />
							<input type="text" class="input-inline input-xxlarge" placeholder="-xxlarge" />
						</form>
						<pre><code class="lang-markup">&lt;form&gt;
	&lt;input type="text" class="input-inline input-tiny" placeholder="-tiny" /&gt;
	&lt;input type="text" class="input-inline input-small" placeholder="-small" /&gt;
	&lt;input type="text" class="input-inline input-medium" placeholder="-medium" /&gt;
	&lt;input type="text" class="input-inline input-large" placeholder="-large" /&gt;
	&lt;input type="text" class="input-inline input-xlarge" placeholder="-xlarge" /&gt;
	&lt;input type="text" class="input-inline input-xxlarge" placeholder="-xxlarge" /&gt;
&lt;/form&gt;</code></pre>

					</section>
					<hr/>

					<section>
						<h2>Advanced Forms</h2>
						<h3>Custom Checkboxes</h3>
						<form>
							<label class="input-checkbox input-inline">
								<input type="checkbox" />
								<i></i><span>Checkbox 1</span>
							</label>
							<label class="input-checkbox input-inline">
								<input type="checkbox" />
								<i></i><span>Checkbox 2</span>
							</label>
						</form>

						<pre><code class="lang-markup">&lt;form&gt;
	&lt;label class="input-checkbox input-inline"&gt;
		&lt;input type="checkbox" /&gt;
		&lt;i&gt;&lt;/i&gt;&lt;span&gt;Checkbox 1&lt;/span&gt;
	&lt;/label&gt;
	&lt;label class="input-checkbox input-inline"&gt;
		&lt;input type="checkbox" /&gt;
		&lt;i&gt;&lt;/i&gt;&lt;span&gt;Checkbox 2&lt;/span&gt;
	&lt;/label&gt;
&lt;/form&gt;</code></pre>

						<h3>Custom Radios</h3>
						<form>
							<label class="input-radio input-inline">
								<input type="radio" name="radioset2" />
								<i></i><span>Radio 1</span>
							</label>
							<label class="input-radio input-inline">
								<input type="radio" name="radioset2" />
								<i></i><span>Radio 2</span>
							</label>
						</form>

						<pre><code class="lang-markup">&lt;form&gt;
	&lt;label class="input-radio input-inline"&gt;
		&lt;input type="radio" name="radioset2" /&gt;
		&lt;i&gt;&lt;/i&gt;&lt;span&gt;Radio 1&lt;/span&gt;
	&lt;/label&gt;
	&lt;label class="input-radio input-inline"&gt;
		&lt;input type="radio" name="radioset2" /&gt;
		&lt;i&gt;&lt;/i&gt;&lt;span&gt;Radio 2&lt;/span&gt;
	&lt;/label&gt;
&lt;/form&gt;</code></pre>

						<h3>Custom Select dropdowns</h3>
						<form>
							<select class="input-select input-inline input-xxlarge">
								<option>Option 1</option>
								<option>Option 2</option>
								<option>Option 3</option>
							</select>
							<input type="text" class="input-xxlarge input-error" placeholder="input-error" />
						</form>

						<pre><code class="lang-markup">&lt;form&gt;
	&lt;select class="input-select input-inline input-xxlarge"&gt;
		&lt;option&gt;Option 1&lt;/option&gt;
		&lt;option&gt;Option 2&lt;/option&gt;
		&lt;option&gt;Option 3&lt;/option&gt;
	&lt;/select&gt;
&lt;/form&gt;</code></pre>

						<h3>Form error</h3>
						<form>
							<input type="text" class="input-xxlarge input-error" placeholder="input-error" />
						</form>

						<pre><code class="lang-markup">&lt;form&gt;
	&lt;input type="text" class="input-xxlarge input-error" placeholder="input-error" /&gt;
&lt;/form&gt;</code></pre>

						<h3>Form validation</h3>
						<p>Form validation is baked right in with jQuery unobtrusive validation.</p>
						<p>Simply add the appropriate <code>data-</code> attributes, and add <code class="lang-markup">&lt;span class="field-validation-valid" data-valmsg-for="controlName" data-valmsg-replace="true"&gt;&lt;/span&gt;</code> near the input.</p>
						<p>Some <code>data-</code> attributes:</p>
						<ul>
							<li><code>data-val="true"</code>: enable unobtrusive validation on this element (should be on every input element you want to validate)</li>
							<li><code>data-val-required="ErrMsg"</code>: makes the input required, and shows the ErrMsg</li>
							<li><code>data-val-mustbetrue="ErrMsg"</code>: makes a checkbox required, and shows the ErrMsg</li>
							<li><code>data-val-length="ErrMsg" data-val-length-min="5" data-val-length-max="15"</code>: sets required string length and associated error message.</li>
							<li><code>data-val-number="ErrMsg"</code>: makes a field required to be a number.</li>
							<li><code>data-val-equalto="ErrMsg" data-val-equalto-other="Fld"</code>: requires one field to match the other (such as password confirm. Fld is a jQuery selector</li>
							<li><code>data-val-regex="ErrMsg" data-val-regex-pattern="^regex$"</code>: Requires the field to match the regex pattern.</li>
						</ul>
						<p>For best results, wrap the label, input, and validator span in <code class="lang-markup">&lt;div class="field-validation"&gt;&lt;/div&gt;</code>.  You can apply sizing classes to the wrapper.</p>
						<form method="POST">
							<div class="field-validation input-xxlarge">
								<label>Required:</label>
								<input data-val="true" data-val-required="This is required." name="required" placeholder="text" type="text" />
								<span class="field-validation-valid" data-valmsg-for="required" data-valmsg-replace="true"></span>
							</div>
							<div class="field-validation">
								<label class="input-checkbox">
									<input data-val="true" data-val-mustbetrue="You must check." name="requiredcheckbox" type="checkbox" value="1" />
									<i></i><span>Required Checkbox</span>
								</label>
								<span class="field-validation-valid" data-valmsg-for="requiredcheckbox" data-valmsg-replace="true"></span>
							</div>
							<div class="field-validation input-xxlarge">
								<label>Required Number:</label>
								<input data-val="true" data-val-required="This is required." data-val-number="May only contain numbers." name="requirednumber" placeholder="e.g. 123" type="text" />
								<span class="field-validation-valid" data-valmsg-for="requirednumber" data-valmsg-replace="true"></span>
							</div>
							<div class="field-validation input-xxlarge">
								<label>Required Regex:</label>
								<input data-val="true" data-val-required="This is required." data-val-regex="Must be a US phone number." name="requiredregex" data-val-regex-pattern="^\d{3}-\d{3}-\d{4}$" placeholder="e.g. 123-456-7890" type="text" />
								<span class="field-validation-valid" data-valmsg-for="requiredregex" data-valmsg-replace="true"></span>
							</div>
							<div class="field-validation input-xxlarge">
								<label>Required Max:</label>
								<input data-val="true" data-val-required="This is required." data-val-length="Must be 5 characters or less." data-val-length-max="5" name="requiredmax" placeholder="max 5 characters" type="text" value="too long" />
								<span class="field-validation-valid" data-valmsg-for="requiredmax" data-valmsg-replace="true"></span>
							</div>
							<button class="btn" type="submit">Submit Test</button>
						</form>
						<pre><code class="lang-markup">&lt;form method="POST"&gt;
	&lt;div class="field-validation input-xxlarge"&gt;
		&lt;label&gt;Required:&lt;/label&gt;
		&lt;input data-val="true" data-val-required="This is required." name="required" placeholder="text" type="text" /&gt;
		&lt;span class="field-validation-valid" data-valmsg-for="required" data-valmsg-replace="true"&gt;&lt;/span&gt;
	&lt;/div&gt;
	&lt;div class="field-validation"&gt;
		&lt;label class="input-checkbox"&gt;
			&lt;input data-val="true" data-val-mustbetrue="You must check." name="requiredcheckbox" type="checkbox" value="1" /&gt;
			&lt;i&gt;&lt;/i&gt;&lt;span&gt;Required Checkbox&lt;/span&gt;
		&lt;/label&gt;
		&lt;span class="field-validation-valid" data-valmsg-for="requiredcheckbox" data-valmsg-replace="true"&gt;&lt;/span&gt;
	&lt;/div&gt;
	&lt;div class="field-validation input-xxlarge"&gt;
		&lt;label&gt;Required Number:&lt;/label&gt;
		&lt;input data-val="true" data-val-required="This is required." data-val-number="May only contain numbers." name="requirednumber" placeholder="e.g. 123" type="text" /&gt;
		&lt;span class="field-validation-valid" data-valmsg-for="requirednumber" data-valmsg-replace="true"&gt;&lt;/span&gt;
	&lt;/div&gt;
	&lt;div class="field-validation input-xxlarge"&gt;
		&lt;label&gt;Required Regex:&lt;/label&gt;
		&lt;input data-val="true" data-val-required="This is required." data-val-regex="Must be a US phone number." name="requiredregex" data-val-regex-pattern="^\d{3}-\d{3}-\d{4}$" placeholder="e.g. 123-456-7890" type="text" /&gt;
		&lt;span class="field-validation-valid" data-valmsg-for="requiredregex" data-valmsg-replace="true"&gt;&lt;/span&gt;
	&lt;/div&gt;
	&lt;div class="field-validation input-xxlarge"&gt;
		&lt;label&gt;Required Max:&lt;/label&gt;
		&lt;input data-val="true" data-val-required="This is required." data-val-length="Must be 5 characters or less." data-val-length-max="5" name="requiredmax" placeholder="max 5 characters" type="text" value="too long" /&gt;
		&lt;span class="field-validation-valid" data-valmsg-for="requiredmax" data-valmsg-replace="true"&gt;&lt;/span&gt;
	&lt;/div&gt;
	&lt;button class="btn" type="submit"&gt;Submit Test&lt;/button&gt;
&lt;/form&gt;</code></pre>

					</section>
					<hr/>

					<section>
						<h3>Search Forms</h3>
						<p>Apply special styling to search form elements using the <code>.input-search</code> and <code>.btn-search</code> classes. You&#39;ll also need to use the <code>.input-inline</code> class.</p>
						<form>
							<label class="screen-reader" for="search">Search this site</label>
							<input type="text" class="input-search no-margin-bottom" name="search" placeholder="Search this site..." value="" />
							<button type="submit" class="btn-search">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 32 32"><path d="M31.008 27.23l-7.58-6.446c-.784-.705-1.622-1.03-2.3-.998C22.92 17.69 24 14.97 24 12 24 5.37 18.627 0 12 0S0 5.37 0 12c0 6.626 5.374 12 12 12 2.973 0 5.692-1.082 7.788-2.87-.03.676.293 1.514.998 2.298l6.447 7.58c1.105 1.226 2.908 1.33 4.008.23s.997-2.903-.23-4.007zM12 20c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"/></svg>
								<span class="icon-fallback-text">?</span>
							</button>
						</form>

						<pre><code class="lang-markup">&lt;input type=&quot;text&quot; class=&quot;input-inline input-search no-margin-bottom&quot; id=&quot;search&quot; name=&quot;search&quot; placeholder=&quot;Search this site...&quot; value=&quot;&quot; /&gt;
&lt;button type=&quot;submit&quot; class=&quot;btn-search&quot; id=&quot;searchsubmit&quot;&gt;
	&lt;svg xmlns=&quot;http://www.w3.org/2000/svg&quot; class=&quot;icon&quot; viewBox=&quot;0 0 32 32&quot;&gt;&lt;path d=&quot;M31.008 27.23l-7.58-6.446c-.784-.705-1.622-1.03-2.3-.998C22.92 17.69 24 14.97 24 12 24 5.37 18.627 0 12 0S0 5.37 0 12c0 6.626 5.374 12 12 12 2.973 0 5.692-1.082 7.788-2.87-.03.676.293 1.514.998 2.298l6.447 7.58c1.105 1.226 2.908 1.33 4.008.23s.997-2.903-.23-4.007zM12 20c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z&quot;/&gt;&lt;/svg&gt;
	&lt;span class=&quot;icon-fallback-text&quot;&gt;Search&lt;/span&gt;
&lt;/button&gt;</code></pre>
						<hr/>
					</section>
					<hr/>

					<section>
						<h2>SVGs</h2>
						<p>This boilerplate includes SVG detection <code>(detects.js)</code> that adds an <code>.svg</code> class to the <code>html</code> element when supported. A gulp build system can be used to optimize SVGs, and can also generate SVG sprites from individual SVG files.</p>
						<p>Use the <code>.icon</code> class hide SVGs on unsupported browsers, and the <code>.icon-fallback-text</code> class to display fallback text in it&#39;s place.</p>
						<h3>Inline SVGs</h3>
						<p>Open the SVG file in your text editor of choice, and then literally copy and paste the content from the file into your markup.</p>
						<pre><code class="lang-markup">&lt;svg class=&quot;icon&quot;&gt;...&lt;/svg&gt;
&lt;span class=&quot;icon-fallback-text&quot;&gt;Descriptive Text&lt;/span&gt;</code></pre>
						<h3>SVG Sprites</h3>
						<p>Add the contents of your sprite to the markup in a hidden container directly after the opening <code>&lt;body&gt;</code> tag.</p>
						<pre><code class="lang-markup">&lt;div hidden&gt;
	&lt;svg&gt;...&lt;/svg&gt;
&lt;/div&gt;</code></pre>
						<p>To use an icon, simply reference its ID using the <code>&lt;use&gt;</code> element.</p>
						<pre><code class="lang-markup">&lt;svg class=&quot;icon&quot;&gt;
	&lt;use xlink:href=&quot;#icon-logo&quot;&gt;&lt;/use&gt;
&lt;/svg&gt;
&lt;span class=&quot;icon-fallback-text&quot;&gt;My Awesome Website&lt;/span&gt;</code></pre>
						<h3>External SVG sprites</h3>
						<p>Instead of embedding, you can link to the SVG as an external file. However, this method is not supported in Internet Explorer and requires you to use <a href="https://github.com/jonathantneal/svg4everybody">svg4everybody</a>, a JavaScript polyfill.</p>
						<p>While this will gain you some browser caching benefits, perceived load times are better using the inlined sprite approach, as the icons are rendered immediately.</p>
						<p><a href="http://gomakethings.com/using-svgs/">Learn more about using SVGs.</a></p>
						<hr/>
					</section>
					<hr/>

					<section>
						<h2>Alignment, Spacing &amp; Visibility</h2>
						<p>You can adjust text alignment, spacing, and visibility using a few simple CSS classes.</p>
						<h3>Text Alignment</h3>
						<table>
							<thead>
								<tr>
									<th>Class</th>
									<th>Alignment</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><code>.text-left</code></td>
									<td>Left</td>
								</tr>
								<tr>
									<td><code>.text-center</code></td>
									<td>Centered</td>
								</tr>
								<tr>
									<td><code>.text-right</code></td>
									<td>Right</td>
								</tr>
								<tr>
									<td><code>.text-right-medium</code></td>
									<td>Right above 40em</td>
								</tr>
							</tbody>
						</table>
						<h3>Floats</h3>
						<table>
							<thead>
								<tr>
									<th>Class</th>
									<th>Float</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><code>.float-left</code></td>
									<td>Left</td>
								</tr>
								<tr>
									<td><code>.float-center</code></td>
									<td>Centered</td>
								</tr>
								<tr>
									<td><code>.float-right</code></td>
									<td>Right</td>
								</tr>
							</tbody>
						</table>
						<p><em>Clear floats by wrapping floated content in a <code>&lt;div&gt;</code> with the <code>.clearfix</code> class.</em></p>
						<pre><code class="lang-markup">&lt;div class=&quot;clearfix&quot;&gt;
	&lt;button class=&quot;float-right&quot;&gt;Floated to the Right&lt;/button&gt;
	&lt;button&gt;Not floated&lt;/button&gt;
&lt;/div&gt;</code></pre>
						<h3>Margins</h3>
						<table>
							<thead>
								<tr>
								<th>Class</th>
								<th>Margin</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><code>.no-margin-top</code></td>
									<td>top: <code>0</code></td>
								</tr>
								<tr>
									<td><code>.no-margin-bottom</code></td>
									<td>bottom: <code>0</code></td>
								</tr>
								<tr>
									<td><code>.margin-top</code></td>
									<td>top: <code>1.5625em</code></td>
								</tr>
								<tr>
									<td><code>.margin-bottom</code></td>
									<td>bottom: <code>1.5625em</code></td>
								</tr>
								<tr>
									<td><code>.margin-bottom-small</code></td>
									<td>bottom: <code>0.5em</code></td>
								</tr>
								<tr>
									<td><code>.margin-bottom-large</code></td>
									<td>bottom: <code>2em</code></td>
								</tr>
							</tbody>
						</table>
						<h3>Padding</h3>
						<table>
							<thead>
								<tr>
									<th>Class</th>
									<th>padding</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><code>.no-padding-top</code></td>
									<td>top: <code>0</code></td>
								</tr>
								<tr>
									<td><code>.no-padding-bottom</code></td>
									<td>bottom: <code>0</code></td>
								</tr>
								<tr>
									<td><code>.padding-top</code></td>
									<td>top: <code>1.5625em</code></td>
								</tr>
								<tr>
									<td><code>.padding-top-small</code></td>
									<td>top: <code>0.5em</code></td>
								</tr>
								<tr>
									<td><code>.padding-top-large</code></td>
									<td>top: <code>2em</code></td>
								</tr>
								<tr>
									<td><code>.padding-bottom</code></td>
									<td>bottom: <code>1.5625em</code></td>
								</tr>
								<tr>
									<td><code>.padding-bottom-small</code></td>
									<td>bottom: <code>0.5em</code></td>
								</tr>
								<tr>
									<td><code>.padding-bottom-large</code></td>
									<td>bottom: <code>2em</code></td>
								</tr>
							</tbody>
						</table>
						<h3>Size</h3>
						<table>
							<thead>
								<tr>
									<th>Class</th>
									<th>sample</th>
									<th>sizing</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><code>.text-xlarge</code></td>
									<td><span class="text-xlarge">text-xlarge</span></td>
									<td>font-size: <code>1.6875em</code></td>
								</tr>
								<tr>
									<td><code>.text-large</code></td>
									<td><span class="text-large">text-large</span></td>
									<td>font-size: <code>1.3125em</code></td>
								</tr>
								<tr>
									<td><code>.text-small</code></td>
									<td><span class="text-small">text-small</span></td>
									<td>font-size: <code>0.9375em</code></td>
								</tr>
								<tr>
									<td><code>.text-xsmall</code></td>
									<td><span class="text-xsmall">text-xsmall</span></td>
									<td>font-size: <code>0.6875em</code></td>
								</tr>
							</tbody>
						</table>
						<h3>Visibility</h3>
						<p>Hide content using the <code>[hidden]</code> attribute.</p>
						<pre><code class="lang-markup">&lt;div hidden&gt;This is removed from the markup.&lt;/div&gt;</code></pre>
						<p>If you have text that you don&#39;t want displayed on screen, but that should still be in the markup for screen readers (for example, a search form label), simply apply the <code>.screen-reader</code> class.</p>
						<pre><code class="lang-markup">&lt;form&gt;
	&lt;label class=&quot;screen-reader&quot;&gt;Search this site&lt;/label&gt;
	&lt;input type=&quot;text&quot; placeholder=&quot;Search this site...&quot; /&gt;
	&lt;input type=&quot;submit&quot; /&gt;
&lt;/form&gt;</code></pre>
						<p>For visually hidden content that should become visible on focus (such as a <a href="http://webaim.org/techniques/skipnav/">skip nav link</a> for sighted users navigating by keyboard), add the <code>.screen-reader-focusable</code> class.</p>
						<pre><code class="lang-markup">&lt;a class=&quot;screen-reader screen-reader-focusable&quot; href=&quot;#main&quot;&gt;Skip to content&lt;/a&gt;</code></pre>
					</section>
