<?php if( empty( $menu ) ) return ''; ?>
							<div class="icon-link-wrap">
								<ul class="icon-link-list">
<?php foreach( $menu as $item ) : ?>
									<li class="icon link-<?php
										print $item['id'];
										if( ! empty( $item['subtitle'] ) ) print '" title="' . $item['subtitle'];
									?>"><a href="<?=
										$item['url']
									?>" class="submit"><span class="icon-image"></span><span><?=
										$item['title']
									?></a></li>
<?php endforeach; ?>
								</ul>
							</div>
