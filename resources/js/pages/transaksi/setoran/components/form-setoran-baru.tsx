import FormInputCurrency from '@/components/form-input-currency'
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog'
import { alertApp } from '@/components/utils'
import { useForm } from '@inertiajs/react'
import { Loader2, Save } from 'lucide-react'
import { useEffect, useRef } from 'react'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    onChange: () => void
    model: any
}& Record<string, any>
export default function FormSetoranBaru({ open, setOpen, onChange, model}: props) {
    const { data, setData, post, reset, errors, clearErrors, processing } = useForm({
        anggota : '',
        jenisTabungan : '',
        nominal : ''
    })
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        post(route('transaksi.setoran.setoran-baru'), {
            preserveScroll: true,
            onSuccess: (e) => {
                clearErrors()
                reset(),
                alertApp(e)
                onChange?.()
                setOpen(false)
            },
            onError: (e) => {
                const firstErrorKey = Object.keys(e)[0]
                if (firstErrorKey) {
                    formRefs.current[firstErrorKey]?.focus()
                }
            },
        })
    }

    useEffect(() => {
        if (open && model) {
            setData((prev) => ({ ...prev, anggota: model.anggota, jenisTabungan: model.jenisTabungan }))
        }
    }, [open, model])
    return (
        <Dialog open={open} onOpenChange={(val) => {
            setOpen(val);
            if (!val && onChange) onChange();
        }}>
            <DialogContent className='w-2xl'>
                <form onSubmit={handleForm}>
                    <DialogHeader>
                        <DialogTitle>Form Setoran</DialogTitle>
                        <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                    </DialogHeader>
                    <div className="mt-5 mb-2">
                        <FormInputCurrency
                            id="nominal"
                            type="text"
                            value={data.nominal}
                            onChange={(value, floatValue) => setData((prevData: any) => ({ ...prevData, nominal: floatValue }))}
                            inputRef={(el) => {if (formRefs.current) {formRefs.current['nominal'] = el}}}
                            placeholder="Masukkan nominal"
                            error={errors.nominal}
                            autoComplete='off'
                            required
                        />
                    </div>
                    <DialogFooter>
                        <div className="flex items-center mt-5">
                            <Button type="submit" disabled={processing}> {processing ? <Loader2 className="animate-spin" /> : <Save /> } Simpan</Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
