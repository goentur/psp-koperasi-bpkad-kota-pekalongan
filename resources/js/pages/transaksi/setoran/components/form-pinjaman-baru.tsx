import FormCalendar from '@/components/form-calendar'
import FormInput from '@/components/form-input'
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
export default function FormPinjamanBaru({ open, setOpen, onChange, model}: props) {
    const { data, setData, post, reset, errors, clearErrors, processing } = useForm({
        anggota : '',
        nominal : '',
        jangkaWaktu : '',
        tanggalPendaftaran : new Date(),
        tanggalPersetujuan : new Date(),
    })
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        post(route('transaksi.pinjaman-baru'), {
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
            setData((prev) => ({ ...prev, anggota: model }))
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
                        <DialogTitle>Form Pinjaman Baru</DialogTitle>
                        <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                    </DialogHeader>
                    <div className="grid grid-cols-2 gap-2 mt-5 mb-2">
                        <FormInputCurrency
                            id="nominal"
                            type="text"
                            value={data.nominal}
                            onChange={(value, floatValue) => setData((prevData: any) => ({ ...prevData, nominal: floatValue }))}
                            inputRef={(el) => {if (formRefs.current) {formRefs.current['nominal'] = el}}}
                            placeholder="Masukkan nominal"
                            error={errors.nominal}
                            autoComplete='off'
                            autoFocus
                            required
                        />
                        <FormInput
                            id="jangkaWaktu"
                            type="text"
                            value={data.jangkaWaktu}
                            onChange={(e) => setData((prevData: any) => ({ ...prevData, jangkaWaktu: e.target.value }))}
                            inputRef={(el) => {if (formRefs.current) {formRefs.current['jangkaWaktu'] = el}}}
                            placeholder="Masukkan jangka waktu"
                            error={errors.jangkaWaktu}
                            required
                        />
                        <FormCalendar
                            id="tanggalPendaftaran"
                            value={data.tanggalPendaftaran}
                            onChange={(e) => setData((prevData: any) => ({ ...prevData, tanggalPendaftaran: e }))}
                            inputRef={(el) => {if (formRefs.current) {formRefs.current['tanggalPendaftaran'] = el}}}
                            placeholder="Pilih tanggal pendaftaran"
                            error={errors.tanggalPendaftaran}
                            required
                            autoComplete='off'
                            tanggalSelanjutnya={false}
                        />
                        <FormCalendar
                            id="tanggalPersetujuan"
                            value={data.tanggalPersetujuan}
                            onChange={(e) => setData((prevData: any) => ({ ...prevData, tanggalPersetujuan: e }))}
                            inputRef={(el) => {if (formRefs.current) {formRefs.current['tanggalPersetujuan'] = el}}}
                            placeholder="Pilih tanggal persetujuan"
                            error={errors.tanggalPersetujuan}
                            required
                            autoComplete='off'
                            tanggalSelanjutnya={false}
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
