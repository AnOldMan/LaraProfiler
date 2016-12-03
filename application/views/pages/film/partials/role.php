<?php

if( empty( $data ) ) return '';
$roles = array(); $i = 100;
foreach( $data as $d )
{
    if( empty( $d['person'] ) ) continue;
    $k = empty( $d['sortorder'] ) ? $i++ : $d['sortorder'];
    $roles[$k] = array(
        'role' =>  empty( $d['role'] ) ? '' : $d['role'],
        'person' => Person::format_link( $d['person']['stub'], $d['person']['fullname'], $d['person']['birthyear'] )
    );
    if( ! empty( $d['creditedas'] ) )
        $roles[$k]['creditedas'] = 'Credited As: ' . $d['creditedas'];
    $d['voice'] = empty( $d['voice'] ) ? '' : Person::link( $d['voice'] );
    if( ! empty( $d['voice'] ) )
        $roles[$k]['voice'] = 'Voice: ' . $d['voice'];
    $d['uncredited'] = empty( $d['uncredited'] ) ? '' : Person::link( $d['uncredited'] );
    if( ! empty( $d['uncredited'] ) )
        $roles[$k]['uncredited'] = 'Not Credited: ' . $d['uncredited'];    
}
if( empty( $roles ) ) return '';
sort( $roles );

?>
                        <label><?= count( $roles ) == 1 ? 'Role' : 'Roles' ?>:</label>
                        <dl class="dd-role">
<?php foreach( $roles as $d ) : ?>
                            <dt><?= array_shift( $d ) ?>:</dt>
                            <dd><?= implode( '<br/>', $d ) ?></dd>
<?php endforeach; ?>
                        </dl>