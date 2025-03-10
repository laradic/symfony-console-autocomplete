<?
/** @noinspection ALL */
// formatter:off
?>_<?= $vars['script'] ?>()
{
    local cur script coms opts com
    COMPREPLY=()
    _get_comp_words_by_ref -n : cur words

    # for an alias, get the real script behind it
    if [[ $(type -t ${words[0]}) == "alias" ]]; then
        script=$(alias ${words[0]} | sed -E "s/alias ${words[0]}='(.*)'/\1/")
    else
        script=${words[0]}
    fi

    # lookup for command
    for word in ${words[@]:1}; do
        if [[ $word != -* ]]; then
            com=$word
            break
        fi
    done

    # completing for an option
    if [[ ${cur} == --* ]] ; then
        opts="<?= join(' ', $vars['options_global']) ?>"

        case "$com" in
<? foreach ($vars['options_command'] as $command => $options): ?>

            <?= $command ?>)
            opts="${opts} <?= join(' ', array_diff($options, $vars['options_global'])) ?>"
            ;;
<? endforeach; ?>

        esac

        COMPREPLY=($(compgen -W "${opts}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0;
    fi

    # completing for a command
    if [[ $cur == $com ]]; then
        coms="<?= join(' ', $vars['commands']) ?>"

        COMPREPLY=($(compgen -W "${coms}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0
    fi
}

<? foreach ($vars['tools'] as $tool): ?>
complete -o default -F _<?= $vars['script'] ?> <?= $tool ?>

<? endforeach; ?>
