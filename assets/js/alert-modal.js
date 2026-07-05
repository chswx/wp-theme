document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('alert-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalDesc = document.getElementById('modal-description');
    const modalInstr = document.getElementById('modal-instructions');

    document.querySelectorAll('.alert-expand').forEach(btn => {
        btn.addEventListener('click', () => {
            const details = btn.closest('li').querySelector('.alert-details');
            const title = btn.closest('li').querySelector('.alert-name').textContent;
            modalTitle.textContent = title;

            const desc = details.querySelector('.alert-description');
            const instr = details.querySelector('.alert-instructions');

            modalDesc.innerHTML = desc ? desc.innerHTML : '';
            modalInstr.innerHTML = instr ? instr.innerHTML : '';

            modal.showModal();
        });
    });

    document.querySelector('.modal-close').addEventListener('click', () => modal.close());
});
